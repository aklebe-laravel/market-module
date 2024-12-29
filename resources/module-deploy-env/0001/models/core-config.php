<?php

use Modules\WebsiteBase\app\Models\CoreConfig;

return [
    // class of eloquent model
    'model'                => CoreConfig::class,
    // update data if exists and data differ (default false)
    'update'               => true,
    // if update true only: don't update this fields
    'ignore_update_fields' => [
        'value',
    ],
    // columns to check if data already exists (AND WHERE)
    'uniques'              => ['store_id', 'path'],
    // data rows itself
    'data'                 => [
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image.width',
            'value'       => '800',
            'label'       => 'Product image width',
            'description' => 'Default product image width',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image.height',
            'value'       => '600',
            'label'       => 'Product image height',
            'description' => 'Default product image height',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image.quality',
            'value'       => '90',
            'label'       => 'Product image quality',
            'description' => 'Default product image quality',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image_thumb_medium.width',
            'value'       => '400',
            'label'       => 'Medium thumb width',
            'description' => 'Default product medium thumb image width',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image_thumb_medium.height',
            'value'       => '400',
            'label'       => 'Medium thumb height',
            'description' => 'Default product medium thumb image height',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image_thumb_medium.quality',
            'value'       => '90',
            'label'       => 'Medium thumb quality',
            'description' => 'Default product medium thumb image quality',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image_thumb_small.width',
            'value'       => '140',
            'label'       => 'Small thumb width',
            'description' => 'Default product small thumb image width',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image_thumb_small.height',
            'value'       => '140',
            'label'       => 'Small thumb height',
            'description' => 'Default product small thumb image height',
        ],
        [
            'store_id'    => null,
            'path'        => 'catalog.product.image_thumb_small.quality',
            'value'       => '90',
            'label'       => 'Small thumb quality',
            'description' => 'Default product small thumb image quality',
        ],
        // special for store 1
        [
            'store_id'    => 1,
            'path'        => 'catalog.product.image.width',
            'value'       => '1200',
            'label'       => 'Product image width',
            'description' => 'Default product image width',
        ],
        // special for store 1
        [
            'store_id'    => 1,
            'path'        => 'catalog.product.image.height',
            'value'       => '1200',
            'label'       => 'Product image height',
            'description' => 'Default product image height',
        ],
        [
            'store_id'    => null,
            'path'        => 'users.profiles.media.enabled',
            'value'       => '0',
            'label'       => 'Enable media in profiles',
            'form_input'  => 'switch',
            'description' => 'Enables media listing in user profiles',
        ],
        [
            'store_id'    => null,
            'path'        => 'users.profiles.products.enabled',
            'value'       => '0',
            'label'       => 'Enable products in profiles',
            'form_input'  => 'switch',
            'description' => 'Enables product listing in user profiles',
        ],
        [
            'store_id'    => null,
            'path'        => 'site.rating.enabled',
            'value'       => '1',
            'label'       => 'Enable ratings in general',
            'form_input'  => 'switch',
            'description' => 'Enables site ratings. If this if disabled, all ratings are disabled.',
        ],
        [
            'store_id'    => null,
            'path'        => 'user.rating.enabled',
            'value'       => '1',
            'label'       => 'Enable user ratings',
            'form_input'  => 'switch',
            'description' => 'Enables user ratings',
        ],
        [
            'store_id'    => null,
            'path'        => 'product.rating.enabled',
            'value'       => '1',
            'label'       => 'Enable product ratings',
            'form_input'  => 'switch',
            'description' => 'Enables product ratings',
        ],
    ],
];

