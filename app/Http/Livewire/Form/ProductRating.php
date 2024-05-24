<?php

namespace Modules\Market\app\Http\Livewire\Form;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\Rating;

class ProductRating extends NativeObjectBase
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
     * @param  mixed  $livewireId
     * @param  mixed  $itemId
     * @return void
     */
    #[On('accept-rating')]
    public function acceptRating(mixed $livewireId, mixed $itemId): void
    {
        $userId = Auth::id();

        if ($validatedData = $this->validateForm()) {
            $rating1 = Rating::updateOrCreate([
                'model'          => Product::class,
                'model_sub_code' => Product::RATING_SUB_CODE_PUBLIC_PRODUCT,
                'user_id'        => $userId,
                'model_id'       => $itemId,
            ], [
                'value' => (int) $validatedData['rating5_public_product'] * 20,
            ]);
            $rating2 = Rating::updateOrCreate([
                'model'          => Product::class,
                'model_sub_code' => Product::RATING_SUB_CODE_CONDITION,
                'user_id'        => $userId,
                'model_id'       => $itemId,
            ], [
                'value' => (int) $validatedData['rating5_condition'] * 20,
            ]);
        }

        $this->closeForm();

    }

}
