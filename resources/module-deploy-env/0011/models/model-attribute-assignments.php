<?php

use Modules\WebsiteBase\app\Models\ModelAttribute;
use Modules\WebsiteBase\app\Models\ModelAttributeAssignment;

return [
    // class of eloquent model
    'model'   => ModelAttributeAssignment::class,
    // update data if exists and data differ (default false)
    'update'  => true,
    // columns to check if data already exists (AND WHERE)
    'uniques' => ['model', 'model_attribute_id'],
    // data rows itself
    'data'    => [
        [
            'model'              => 'Modules\Market\app\Models\Product',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'currency')->first()->getKey(),
            'attribute_input'    => 'select',
            'description'        => 'Currency',
        ],
        [
            'model'              => 'App\Models\User',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'payment_method')->first()->getKey(),
            'attribute_input'    => 'select',
        ],
        [
            'model'              => 'App\Models\User',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'shipping_method')->first()->getKey(),
            'attribute_input'    => 'select',
        ],
    ],
];

