<?php

use Modules\Market\app\Models\ShippingMethod;

return [
    // class of eloquent model
    'model'                => ShippingMethod::class,
    // update data if exists and data differ (default false)
    'update'               => true,
    // if update true only: don't update this fields
    'ignore_update_fields' => [
        'value',
    ],
    // columns to check if data already exists (AND WHERE)
    'uniques'              => ['code'],
    // data rows itself
    'data'                 => [
        [
            'name'        => 'Free',
            'code'        => 'free',
            'description' => 'kostenlos',
        ],
        [
            'name'        => 'Negotiable',
            'code'        => 'negotiable',
            'description' => 'Verhandelbar',
        ],
        [
            'name'        => 'Digital',
            'code'        => 'digital',
            'description' => 'digital (Email, Download, ...)',
        ],
        [
            'name'        => 'DHL',
            'code'        => 'dhl',
            'description' => 'DHL Versand',
        ],
        [
            'name'        => 'UPS',
            'code'        => 'ups',
            'description' => 'UPS Versand',
        ],
        [
            'name'        => 'GLS',
            'code'        => 'gls',
            'description' => 'GLS Versand',
        ],
        [
            'name'        => 'Self Collect',
            'code'        => 'self_collect',
            'description' => 'Selbstabholer',
        ],
        [
            'name'        => 'Private',
            'code'        => 'private',
            'description' => 'Privat',
        ],
    ],
];

