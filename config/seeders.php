<?php

return [
    'core_config' => [
        'count' => 5,
    ],
    'acl_groups'  => [
        'count' => 5,
    ],
    'users'       => [
        'count'       => 50,
        // max addresses per user to add
        'addresses'   => [
            'count'         => 10,
            'chance_to_add' => 75, // in percent
        ],
        'media_items' => [
            'image_storage_source_path'            => 'app/seeder/images/samples/products', // (like '/resources/images/samples/products') empty or invalid path = no image creation
            'count_product_images'                 => 30, // total media items per user
            'count_avatar_images'                  => 5, // total media items per user
            'count_min_product_images_per_product' => 6,
            'count_max_product_images_per_product' => 15,
        ],
        // products per user
        'products'    => [
            'sku_prefix' => 'seeder-',
            'min_count' => 5,
            'max_count' => 20,
        ],
    ],
    'categories'  => [
        'count_min_media_items' => 1,
        'count_max_media_items' => 10,
        'count_min_products'    => 5,
        'count_max_products'    => 60,
    ],

];
