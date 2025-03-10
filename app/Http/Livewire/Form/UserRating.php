<?php

namespace Modules\Market\app\Http\Livewire\Form;

use App\Models\User as AppUserModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;
use Modules\Market\app\Models\Rating;
use Modules\Market\app\Models\User as MarketUserModel;
use Modules\Market\app\Services\RatingService;

class UserRating extends NativeObjectBase
{
    /**
     * Singular
     * @var string
     */
    protected string $objectFrontendLabel = 'User Rating';

    /**
     * Plural
     * @var string
     */
    protected string $objectsFrontendLabel = 'User Ratings';

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
        if ((int) $sourceUserId === (int) $itemId) { // no rating for yourself
            $this->addErrorMessage("No user ratings for yourself.");
            Log::warning("User tried to rate himself: $sourceUserId. Remove frontend buttons if any.");
            $this->closeForm();

            return;
        }

        if ($validatedData = $this->validateForm()) {
            /** @var RatingService $userService */
            $userService = app(RatingService::class);

            $rating1 = $userService->saveRating(AppUserModel::class,
                MarketUserModel::RATING_SUB_CODE_TRUST,
                $sourceUserId,
                $itemId,
                (int) $validatedData['rating5_trust'] * 20);
            $rating2 = $userService->saveRating(AppUserModel::class,
                MarketUserModel::RATING_SUB_CODE_WELL_KNOWN,
                $sourceUserId,
                $itemId,
                (int) $validatedData['rating5_well_known'] * 20);
            $rating3 = $userService->saveRating(AppUserModel::class,
                MarketUserModel::RATING_SUB_CODE_OFFER_SUCCESS,
                $sourceUserId,
                $itemId,
                (int) $validatedData['rating5_offer_success'] * 20);

        }

        $this->closeForm();
    }

    /**
     * @param  mixed|null  $id
     *
     * @return JsonResource
     */
    public function initDataSource(mixed $id = null): JsonResource
    {
        // @todo: where to define/load?
        $object = [
            'rating5_trust'         => 1,
            'rating5_well_known'    => 1,
            'rating5_offer_success' => 1,
        ];
        if ($id && is_array($id)) {
            $rating1 = Rating::with([])
                ->where('model', '=', AppUserModel::class)
                ->where('model_sub_code', '=', MarketUserModel::RATING_SUB_CODE_TRUST)
                ->where('user_id', '=', $id['ratingSourceUserId'])
                ->where('model_id', '=', $id['ratingTargetUserId'])
                ->first();
            $rating2 = Rating::with([])
                ->where('model', '=', AppUserModel::class)
                ->where('model_sub_code', '=', MarketUserModel::RATING_SUB_CODE_WELL_KNOWN)
                ->where('user_id', '=', $id['ratingSourceUserId'])
                ->where('model_id', '=', $id['ratingTargetUserId'])
                ->first();
            $rating3 = Rating::with([])
                ->where('model', '=', AppUserModel::class)
                ->where('model_sub_code', '=', MarketUserModel::RATING_SUB_CODE_OFFER_SUCCESS)
                ->where('user_id', '=', $id['ratingSourceUserId'])
                ->where('model_id', '=', $id['ratingTargetUserId'])
                ->first();
            $object = [
                'rating5_trust'         => $rating1 ? (float) ($rating1->value / 20) : 0,
                'rating5_well_known'    => $rating2 ? (float) ($rating2->value / 20) : 0,
                'rating5_offer_success' => $rating3 ? (float) ($rating3->value / 20) : 0,
            ];
        }

        $this->setDataSource(new JsonResource($object));
        return $this->getDataSource();
    }

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        // $defaultSettings = $this->getDefaultFormSettingsByPermission();

        return [
            ... $parentFormData,
            'title'         => '',//__('Submit Product Rating'),
            'form_elements' => [
                'rating5_trust'         => [
                    'html_element' => 'market::rating5',
                    'value'        => 5,
                    'label'        => __('User Trust Rating'),
                    'description'  => __('User Trust Rating Description'),
                    'validator'    => ['nullable', 'numeric', 'Max:5'],
                    'css_group'    => 'col-12',
                ],
                'rating5_well_known'    => [
                    'html_element' => 'market::rating5',
                    'value'        => 4,
                    'label'        => __('User Well Known Rating'),
                    'description'  => __('User Well Known Rating Description'),
                    'validator'    => ['nullable', 'numeric', 'Max:5'],
                    'css_group'    => 'col-12',
                ],
                'rating5_offer_success' => [
                    'html_element' => 'market::rating5',
                    'value'        => 4,
                    'label'        => __('User Offer Rating'),
                    'description'  => __('User Offer Rating Description'),
                    'validator'    => ['nullable', 'numeric', 'Max:5'],
                    'css_group'    => 'col-12',
                ],
            ],
        ];
    }

}
