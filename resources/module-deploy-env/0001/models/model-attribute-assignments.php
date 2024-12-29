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
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'price')->first()->getKey(),
            'attribute_type'     => 'double',
            'attribute_input'    => 'number',
            'description'        => 'Price',
            'form_position'      => '990',
            'form_css'           => 'col-12 col-md-6',
        ],
        [
            'model'              => 'Modules\Market\app\Models\Product',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'currency')->first()->getKey(),
            'attribute_type'     => 'string',
            'attribute_input'    => 'website-base::currency',
            'description'        => 'Currency',
            'form_position'      => '991',
            'form_css'           => 'col-12 col-md-6',
        ],
        [
            'model'              => 'Modules\Market\app\Models\Product',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'description')->first()->getKey(),
            'attribute_type'     => 'text',
            'attribute_input'    => 'textarea',
            'description'        => 'Description',
            'form_position'      => '1100',
            'form_css'           => 'col-12',
        ],
        [
            'model'              => 'App\Models\User',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'payment_method')->first()->getKey(),
            'attribute_type'     => 'integer',
            'attribute_input'    => 'market::payment_method',
            'description'        => 'Preferred Payment Method',
            'form_position'      => '982',
            'form_css'           => 'col-12 col-md-6',
        ],
        [
            'model'              => 'App\Models\User',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'shipping_method')->first()->getKey(),
            'attribute_type'     => 'integer',
            'attribute_input'    => 'market::shipping_method',
            'description'        => 'Preferred Shipping Method',
            'form_position'      => '983',
            'form_css'           => 'col-12 col-md-6',
        ],
    ],
];

