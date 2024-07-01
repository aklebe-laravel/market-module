<?php

return [
    // class of eloquent model
    "model"   => \Modules\WebsiteBase\app\Models\CoreConfig::class,
    // update data if exists and data differ (default false)
    "update"  => false,
    // columns to check if data already exists (AND WHERE)
    "uniques" => ["store_id", "path"],
    // data rows itself
    "data"    => [
        [
            'store_id'    => null,
            "path"        => "product.force_public.enabled",
            "value"       => "1",
            "description" => "Enables linking products to public, even store is not public",
        ],
    ]
];

