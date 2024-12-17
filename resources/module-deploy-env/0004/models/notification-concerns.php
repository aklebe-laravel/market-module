<?php

use Modules\Market\app\Models\NotificationConcern as NotificationConcernAlias;
use Modules\WebsiteBase\app\Models\NotificationConcern;

return [
    // class of eloquent model
    "model"     => NotificationConcern::class,
    // update data if exists and data differ (default false)
    "update"    => true,
    // columns to check if data already exists (AND WHERE)
    "uniques"   => ["reason_code", "notificationTemplate.code", "notificationTemplate.notification_channel"],
    // "uniques"   => ["store_id", "notification_template_id", "reason_code"], // its not working, because the columns have to be declared in each model data below
    // relations to update/create
    "relations" => [
        "store"                 => [
            // relation method which have to exists
            "method"  => "store",
            // column(s) to find specific #sync_relations items below
            "columns" => "code",
            // delete items if not listed here (default: false)
            "delete"  => false,
        ],
        "notification_template" => [
            // relation method which have to exists
            "method"  => "notificationTemplate",
            // column(s) to find specific #sync_relations items below
            "columns" => ["code", "notification_channel"],
            // delete items if not listed here (default: false)
            "delete"  => false,
        ],
    ],
    // data rows itself
    "data"      => [
        [
            "is_enabled"                                => true,
            "reason_code"                               => "market_offer_created",
            "notificationTemplate.code"                 => "market_offer_created",
            "notificationTemplate.notification_channel" => "email",
            "sender"                                    => '',
            "description"                               => "Offer created successfully.",
            "tags"                                      => [
                "shop",
                "offer",
                "created",
            ],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_offer_created", "email"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => "market_offer_rejected",
            "notificationTemplate.code"                 => "market_offer_rejected",
            "notificationTemplate.notification_channel" => "email",
            "sender"                                    => '',
            "description"                               => "Offer rejected.",
            "tags"                                      => [
                "shop",
                "offer",
                "rejected",
            ],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_offer_rejected", "email"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => "market_offer_completed",
            "notificationTemplate.code"                 => "market_offer_completed",
            "notificationTemplate.notification_channel" => "email",
            "sender"                                    => '',
            "description"                               => "Offer completed.",
            "tags"                                      => [
                "shop",
                "offer",
                "completed",
                "success",
            ],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_offer_completed", "email"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_TRADER,
            "notificationTemplate.code"                 => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_TRADER,
            "notificationTemplate.notification_channel" => "email",
            "sender"                                    => '',
            "description"                               => "User assigned to Trader.",
            "tags"                                      => [
                "shop",
                "trader",
                "customer",
            ],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_user_assigned_to_trader", "email"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_ACL_GROUP,
            "notificationTemplate.code"                 => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_ACL_GROUP,
            "notificationTemplate.notification_channel" => "email",
            "sender"                                    => '',
            "description"                               => "User assigned to acl group.",
            "tags"                                      => [
                "user",
                "customer",
                "group",
                "assign",
            ],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_user_assigned_to_acl_group", "email"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => 'market_media_item_import',
            "notificationTemplate.code"                 => 'market_media_item_import',
            "notificationTemplate.notification_channel" => "email",
            "sender"                                    => '',
            "description"                               => "Import completed.",
            "tags"                                      => [],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_media_item_import", "email"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => "market_offer_created",
            "notificationTemplate.code"                 => "market_offer_created",
            "notificationTemplate.notification_channel" => "telegram",
            "sender"                                    => '',
            "description"                               => "Offer created successfully.",
            "tags"                                      => [],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_offer_created", "telegram"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => "market_offer_rejected",
            "notificationTemplate.code"                 => "market_offer_rejected",
            "notificationTemplate.notification_channel" => "telegram",
            "sender"                                    => '',
            "description"                               => "Offer rejected.",
            "tags"                                      => [],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_offer_rejected", "telegram"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => "market_offer_completed",
            "notificationTemplate.code"                 => "market_offer_completed",
            "notificationTemplate.notification_channel" => "telegram",
            "sender"                                    => '',
            "description"                               => "Offer completed.",
            "tags"                                      => [],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_offer_completed", "telegram"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_TRADER,
            "notificationTemplate.code"                 => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_TRADER,
            "notificationTemplate.notification_channel" => "telegram",
            "sender"                                    => '',
            "description"                               => "User assigned to Trader.",
            "tags"                                      => [],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_user_assigned_to_trader", "telegram"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_ACL_GROUP,
            "notificationTemplate.code"                 => NotificationConcernAlias::REASON_CODE_USER_ASSIGNED_TO_ACL_GROUP,
            "notificationTemplate.notification_channel" => "telegram",
            "sender"                                    => '',
            "description"                               => "User assigned to acl group.",
            "tags"                                      => [],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_user_assigned_to_acl_group", "telegram"],
                ],
            ],
        ],
        [
            "is_enabled"                                => true,
            "reason_code"                               => 'market_media_item_import',
            "notificationTemplate.code"                 => 'market_media_item_import',
            "notificationTemplate.notification_channel" => "telegram",
            "sender"                                    => '',
            "description"                               => "Import completed.",
            "tags"                                      => [],
            "meta_data"                                 => [],
            "#sync_relations"                           => [
                "store"                 => [
                    "default",
                ],
                "notification_template" => [
                    ["market_media_item_import", "telegram"],
                ],
            ],
        ],
    ],
];
