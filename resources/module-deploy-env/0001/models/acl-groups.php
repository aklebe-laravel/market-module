<?php

use Modules\Acl\app\Models\AclGroup;

return [
    // class of eloquent model
    "model"     => AclGroup::class,
    // update data if exists and data differ (default false)
    "update"    => false,
    // columns to check if data already exists (AND WHERE)
    "uniques"   => ["name"],
    // relations to update/create
    "relations" => [
        "res" => [
            // relation method which have to exists
            "method" => "aclResources",
            // column(s) to find specific #sync_relations items below
            "columns" => "code",
            // delete items if not listed here (default: false)
            "delete" => false,
        ],
    ],
    // data rows itself
    "data"      => [
        [
            "name"            => "Traders",
            "description"     => "Traders",
            "#sync_relations" => [
                "res" => [
                    "trader"
                ]
            ]
        ],
        [
            "name"            => "Product Managers",
            "description"     => "Allowed to manage products",
            "#sync_relations" => [
                "res" => [
                    "trader",
                    "staff",
                    "manage_products"
                ]
            ]
        ],
    ]
];
