<?php

namespace Modules\Market\app\Http\Livewire\Form;

use App\Models\User as AppUserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;
use Modules\Market\app\Models\User as MarketUserModel;
use Modules\Market\app\Services\RatingService;

class UserRating extends NativeObjectBase
{
    /**
     * This form is opened by default.
     *
     * @var bool
     */
    public bool $isFormOpen = true;

    /**
     * Decides form can send by key ENTER
     *
     * @var bool
     */
    public bool $canKeyEnterSendForm = true;

    /**
     * @var array
     */
    public array $formActionButtons = [];

    /**
     * @param  mixed  $itemId
     *
     * @return void
     */
    #[On('accept-rating')]
    public function acceptRating(mixed $itemId): void
    {
        $sourceUserId = Auth::id();
        if ((int)$sourceUserId === (int)$itemId) { // no rating for yourself
            $this->addErrorMessage("No user ratings for yourself.");
            Log::warning("User tried to rate himself: $sourceUserId. Remove frontend buttons if any.");
            $this->closeForm();

            return;
        }

        if ($validatedData = $this->validateForm()) {
            /** @var RatingService $userService */
            $userService = app(RatingService::class);

            $rating1 = $userService->saveRating(AppUserModel::class, MarketUserModel::RATING_SUB_CODE_TRUST,
                $sourceUserId, $itemId, (int) $validatedData['rating5_trust'] * 20);
            $rating2 = $userService->saveRating(AppUserModel::class, MarketUserModel::RATING_SUB_CODE_WELL_KNOWN,
                $sourceUserId, $itemId, (int) $validatedData['rating5_well_known'] * 20);
            $rating3 = $userService->saveRating(AppUserModel::class, MarketUserModel::RATING_SUB_CODE_OFFER_SUCCESS,
                $sourceUserId, $itemId, (int) $validatedData['rating5_offer_success'] * 20);

        }

        $this->closeForm();
    }

}
