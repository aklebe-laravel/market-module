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
            'path'     => 'catalog.product.image.width',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image.height',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image.quality',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image_thumb_medium.width',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image_thumb_medium.height',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image_thumb_medium.quality',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image_thumb_small.width',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image_thumb_small.height',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'catalog.product.image_thumb_small.quality',
            'module'   => 'market',
        ],
        [
            'store_id' => 1,
            'path'     => 'catalog.product.image.width',
            'module'   => 'market',
        ],
        [
            'store_id' => 1,
            'path'     => 'catalog.product.image.height',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'users.profiles.media.enabled',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'users.profiles.products.enabled',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'site.rating.enabled',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'user.rating.enabled',
            'module'   => 'market',
        ],
        [
            'store_id' => null,
            'path'     => 'product.rating.enabled',
            'module'   => 'market',
        ],
    ],
];

