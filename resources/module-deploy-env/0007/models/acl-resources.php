<?php

use Modules\Acl\app\Models\AclResource;

return [
    // class of eloquent model
    "model"   => AclResource::class,
    // update data if exists and data differ (default false)
    "update"  => false,
    // columns to check if data already exists (AND WHERE)
    "uniques" => ["code"],
    // data rows itself
    "data"    => [
        [
            "code"        => "rating.product.visible",
            "name"        => "Can see product ratings",
            "description" => "Can see product ratings"
        ],
        [
            "code"        => "rating.user.visible",
            "name"        => "Can see user ratings",
            "description" => "Can see user ratings"
        ],
    ]
];

