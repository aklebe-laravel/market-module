<?php

return [
    // class of eloquent model
    "model"     => \Modules\WebsiteBase\app\Models\Navigation::class,
    // update data if exists and data differ (default false)
    "update"    => true,
    // columns to check if data already exists (AND WHERE)
    "uniques"   => ["code"],
    // relations to update/create
    "relations" => [
        "res" => [
            // relation method which have to exists
            "method"  => "parent",
            // column(s) to find specific #sync_relations items below
            "columns" => "code",
            // delete items if not listed here (default: false)
            "delete"  => false,
        ],
    ],
    // data rows itself
    "data"      => [
        [
            "label"           => "Import",
            "code"            => "Admin-Market-Place-Import-L3",
            "route"           => "manage-data-all",
            "route_params"    => ["MediaItemImport"],
            "icon_class"      => "bi bi-upload",
            "position"        => 1400,
            "#sync_relations" => [
                "res" => [
                    "Admin-Market-Place-Menu-L2",
                ],
            ],
        ],
        [
            "label"           => "Import",
            "code"            => "Market-Shop-Import-Menu-L3",
            "route"           => "manage-data",
            "route_params"    => ['modelName' => 'MediaItemImport'],
            "icon_class"      => "bi bi-upload",
            "position"        => 3000,
            "#sync_relations" => [
                "res" => [
                    "Market-Shop-Menu-L2",
                ],
            ],
        ],
    ],
];
