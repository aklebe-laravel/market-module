<?php

use Modules\WebsiteBase\app\Models\NotificationTemplate;
use Modules\WebsiteBase\app\Services\Notification\Channels\Email;

return [
    // class of eloquent model
    'model'     => NotificationTemplate::class,
    // update data if exists and data differ (default false)
    'update'    => true,
    // columns to check if data already exists (AND WHERE)
    'uniques'   => ['code', 'notification_channel'],
    // relations to update/create
    'relations' => [
        'view_template' => [
            // relation method which have to exists
            'method'  => 'viewTemplate',
            // column(s) to find specific #sync_relations items below
            'columns' => 'code',
            // delete items if not listed here (default: false)
            'delete'  => false,
        ],
    ],
    // data rows itself
    'data'      => [
        [
            'is_enabled'           => true,
            'code'                 => 'market_offer_created',
            'notification_channel' => Email::name,
            'subject'              => 'Offer created: {{ config("app.name") }}',
            'description'          => 'Offer created.',
            '#sync_relations'      => [
                'view_template' => [
                    'email_market_offer_created',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_offer_rejected',
            'notification_channel' => Email::name,
            'subject'              => 'Offer rejected: {{ config("app.name") }}',
            'description'          => 'Offer rejected.',
            '#sync_relations'      => [
                'view_template' => [
                    'email_market_offer_rejected',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_offer_completed',
            'notification_channel' => Email::name,
            'subject'              => 'Offer completed: {{ config("app.name") }}',
            'description'          => 'Offer completed.',
            '#sync_relations'      => [
                'view_template' => [
                    'email_market_offer_completed',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_user_assigned_to_trader',
            'notification_channel' => Email::name,
            'subject'              => 'Welcome as new Trader: {{ config("app.name") }}',
            'description'          => 'User assigned to Trader.',
            '#sync_relations'      => [
                'view_template' => [
                    'email_market_user_assigned_to_trader',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_user_assigned_to_acl_group',
            'notification_channel' => Email::name,
            'subject'              => 'Assigned to new Group(s): {{ config("app.name") }}',
            'description'          => 'User assigned to acl group(s).',
            '#sync_relations'      => [
                'view_template' => [
                    'email_market_user_assigned_to_acl_group',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_media_item_import',
            'notification_channel' => Email::name,
            'subject'              => 'Import completed: {{ config("app.name") }}',
            'description'          => 'Import completed.',
            '#sync_relations'      => [
                'view_template' => [
                    'email_market_media_item_import',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_offer_created',
            'notification_channel' => 'telegram',
            'subject'              => 'Offer created: {{ config("app.name") }}',
            'description'          => 'Offer created.',
            '#sync_relations'      => [
                'view_template' => [
                    'telegram_market_offer_created',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_offer_rejected',
            'notification_channel' => 'telegram',
            'subject'              => 'Offer rejected: {{ config("app.name") }}',
            'description'          => 'Offer rejected.',
            '#sync_relations'      => [
                'view_template' => [
                    'telegram_market_offer_rejected',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_offer_completed',
            'notification_channel' => 'telegram',
            'subject'              => 'Offer completed: {{ config("app.name") }}',
            'description'          => 'Offer completed.',
            '#sync_relations'      => [
                'view_template' => [
                    'telegram_market_offer_completed',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_user_assigned_to_trader',
            'notification_channel' => 'telegram',
            'subject'              => 'Welcome as new Trader: {{ config("app.name") }}',
            'description'          => 'User assigned to Trader.',
            '#sync_relations'      => [
                'view_template' => [
                    'telegram_market_user_assigned_to_trader',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_user_assigned_to_acl_group',
            'notification_channel' => 'telegram',
            'subject'              => 'Assigned to new Group(s): {{ config("app.name") }}',
            'description'          => 'User assigned to acl group(s).',
            '#sync_relations'      => [
                'view_template' => [
                    'telegram_market_user_assigned_to_acl_group',
                ],
            ],
        ],
        [
            'is_enabled'           => true,
            'code'                 => 'market_media_item_import',
            'notification_channel' => 'telegram',
            'subject'              => 'Import completed: {{ config("app.name") }}',
            'description'          => 'Import completed.',
            '#sync_relations'      => [
                'view_template' => [
                    'telegram_market_media_item_import',
                ],
            ],
        ],
    ],
];
