<?php

namespace Modules\Market\app\Services;

use App\Models\User as AppUserModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Modules\Acl\app\Models\AclGroup;
use Modules\Market\app\Jobs\AggregateRatingProcess;
use Modules\Market\app\Models\User as MarketUserModel;
use Modules\SystemBase\app\Services\Base\BaseService;
use Modules\WebsiteBase\app\Models\NotificationConcern;
use Modules\WebsiteBase\app\Services\SendNotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Telegram\Bot\Exceptions\TelegramSDKException;

class UserService extends BaseService
{
    /**
     * @param  bool  $useQueue
     *
     * @return void
     */
    public function aggregateRatings(bool $useQueue = true): void
    {
        if ($useQueue) {
            AggregateRatingProcess::dispatch(AppUserModel::class);
        } else {
            AggregateRatingProcess::dispatchSync(AppUserModel::class);
        }
    }

    /**
     * Assign users to group 'Traders' if they have at least 2x trusted 5-star ratings.
     * How it works:
     * 1) Get all users where have rating with trust, value >= 100 and have shared_id
     * 2) Check rating results at least 2 (2 "trust" ratings)
     * 3)
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws TelegramSDKException
     */
    public function calculateTraders(): void
    {
        // $this->debug('Starting calculation of new traders ...', [__METHOD__]);

        /** @var Builder $builder */
        $builder = app(AppUserModel::class)->with([
            'aclGroups',
            'ratings'
        ])

            // whereHas() to reduce results
            ->whereDoesntHave('aclGroups', function (Builder $query) {
                return $query->whereIn('name', [AclGroup::GROUP_TRADERS]);
            })
            // this can be removed to get all users and check ->ratings->count() is 0, but sql query can build huge with IN() condition
            // special with to fill ->ratings with trusted 5 stars only
            ->whereHas('ratings', function (Builder $query) {
                return $query->where('model_sub_code', '=', MarketUserModel::RATING_SUB_CODE_TRUST)
                    ->where('value', '>=', 100);
            }, count: 2)->whereNotNull('shared_id');

        // chunk to avoid memory overflow
        $builder->chunk(100, function ($users) {
            /** @var MarketUserModel $user */
            foreach ($users as $user) {

                // // !!! Check group instead of Resource here !!!
                // $this->debug(sprintf("User '%s' should become a trader.", $user->name));

                // Get the trader group by name!
                if ($groupTrader = AclGroup::with([])->where('name', '=', AclGroup::GROUP_TRADERS)->first()) {

                    // Assign the user to trader group
                    $user->aclGroups()->attach($groupTrader->getKey());
                    Log::info(sprintf("New Trader assigned to user (%s) '%s' - '%s'", $user->getKey(), $user->name,
                        $user->email));

                    // Remember users who rated this user to make them parent
                    foreach ($user->ratings as $userRating) {
                        if ($userRating->model_sub_code !== MarketUserModel::RATING_SUB_CODE_TRUST) {
                            continue;
                        }

                        // Do not check user already attached, just attach every time like a log!
                        // It happens anyway if he was not in traders list
                        $user->parentReputations()->attach([$userRating->user_id]);
                    }


                    // Sending user an email
                    /** @var SendNotificationService $sendNotificationService */
                    $sendNotificationService = app(SendNotificationService::class);
                    $sendNotificationService->sendNotificationConcern(NotificationConcern::REASON_CODE_USER_ASSIGNED_TO_TRADER,
                        $user, ['user' => $user]);


                } else {
                    $this->error("AclGroup not found: ", [AclGroup::GROUP_TRADERS, __METHOD__]);
                }

            }
        });
    }

}