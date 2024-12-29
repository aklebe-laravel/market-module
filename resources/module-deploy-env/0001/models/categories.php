<?php

use Modules\Market\app\Models\Category;

return [
    // class of eloquent model
    'model'     => Category::class,
    // update data if exists and data differ (default false)
    'update'    => false,
    // columns to check if data already exists (AND WHERE)
    'uniques'   => ['code'],
    // relations to update/create
    'relations' => [
        'res' => [
            // relation method which have to exists
            'method'  => 'parents',
            // column(s) to find specific #sync_relations items below
            'columns' => 'code',
            // delete items if not listed here (default: false)
            'delete'  => false,
        ],
    ],
    // data rows itself
    'data'      => [
        [
            'code'        => 'computer',
            'name'        => 'Computer',
            'store_id'    => 1,
            'description' => 'Computer and Accessoires',
        ],
        [
            'code'            => 'laptop',
            'name'            => 'Laptops',
            'store_id'        => 1,
            'description'     => 'Laptops and Notebooks',
            '#sync_relations' => [
                'res' => [
                    'computer',
                    'electronic',
                ],
            ],
        ],
        [
            'code'            => 'network',
            'name'            => 'Network',
            'store_id'        => 1,
            'description'     => 'Network device and adapters',
            '#sync_relations' => [
                'res' => [
                    'computer',
                ],
            ],
        ],
    ],
];

