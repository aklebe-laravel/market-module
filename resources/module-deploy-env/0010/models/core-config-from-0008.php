<?php

use Modules\WebsiteBase\app\Models\CoreConfig;

return [
    // class of eloquent model
    'model'   => CoreConfig::class,
    // update data if exists and data differ (default false)
    'update'  => true,
    // columns to check if data already exists (AND WHERE)
    'uniques' => ['store_id', 'path'],
    // data rows itself
    'data'    => [
        [
            'store_id' => null,
            'module'   => 'market',
            'path'     => 'product.force_public.enabled',
            'options'  => [
                'form' => [
                    'new_group' => true,
                ],
            ],
        ],
    ],
];

