<?php

namespace Modules\Market\app\Forms;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Form\app\Forms\Base\NativeObjectBase;
use Modules\Market\app\Models\Product;
use Modules\Market\app\Models\Rating;

class ProductRating extends NativeObjectBase
{
    /**
     * Singular
     * @var string
     */
    protected string $objectFrontendLabel = 'Product Rating';

    /**
     * Plural
     * @var string
     */
    protected string $objectsFrontendLabel = 'Product Ratings';

    /**
     * @param  mixed|null  $id
     * @return JsonResource
     */
    public function getJsonResource(mixed $id = null): JsonResource
    {
        // @todo: where to define/load?
        $object = [
            'rating5_condition'      => 1,
            'rating5_public_product' => 1,
        ];
        if ($id && is_array($id)) {
            $rating1 = Rating::with([])
                ->where('model', '=', Product::class)
                ->where('model_sub_code', '=', Product::RATING_SUB_CODE_PUBLIC_PRODUCT)
                ->where('user_id', '=', $id['ratingUserId'])
                ->where('model_id', '=', $id['ratingProductId'])
                ->first();
            $rating2 = Rating::with([])
                ->where('model', '=', Product::class)
                ->where('model_sub_code', '=', Product::RATING_SUB_CODE_CONDITION)
                ->where('user_id', '=', $id['ratingUserId'])
                ->where('model_id', '=', $id['ratingProductId'])
                ->first();
            $object = [
                'rating5_public_product' => $rating1 ? (float) ($rating1->value / 20) : 0,
                'rating5_condition'      => $rating2 ? (float) ($rating2->value / 20) : 0,
            ];
        }

        $this->jsonResource = new JsonResource($object);
        return $this->jsonResource;
    }

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        return [
            ... $parentFormData,
            'title'         => '',//__('Submit Product Rating'),
            'form_elements' => [
                'product_id'             => [
                    'html_element' => 'hidden',
                    'label'        => __('ID'),
                    'validator'    => ['nullable', 'integer'],
                ],
                'rating5_condition'      => [
                    'html_element' => 'market::rating5',
                    'value'        => 5,
                    'label'        => __('Product Condition'),
                    'description'  => __('Product Condition Description'),
                    'validator'    => ['nullable', 'numeric', 'Max:5'],
                    'css_group'    => 'col-12',
                ],
                'rating5_public_product' => [
                    'html_element' => 'market::rating5',
                    'value'        => 4,
                    'label'        => __('Product Universal Rating'),
                    'description'  => __('Product Universal Rating Description'),
                    'validator'    => ['nullable', 'numeric', 'Max:5'],
                    'css_group'    => 'col-12',
                ],
            ],
        ];
    }

}