<?php

use Modules\Market\app\Models\Base\ExtraAttributeModel;
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
            'model'              => 'App\Models\User',
            'model_attribute_id' => ModelAttribute::with([])->where('code', '=', 'currency')->first()->getKey(),
            'attribute_type'     => 'string',
            'attribute_input'    => 'select',
            'description'        => 'Currency',
            'form_position'      => '995',
            'form_css'           => 'col-12 col-md-6',
        ],
    ],
];

