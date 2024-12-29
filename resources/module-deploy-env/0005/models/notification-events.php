<?php

use Modules\WebsiteBase\app\Models\NotificationEvent;

return [
    // class of eloquent model
    'model'     => NotificationEvent::class,
    // update data if exists and data differ (default false)
    'update'    => true,
    // columns to check if data already exists (AND WHERE)
    'uniques'   => ['name', 'event_code'],
    // relations to update/create
    'relations' => [
        'notification_concerns' => [
            // relation method which have to exists
            'method'  => 'notificationConcerns',
            // column(s) to find specific #sync_relations items below
            'columns' => 'reason_code',
            // delete items if not listed here (default: false)
            'delete'  => false,
        ],
        'acl_resources'         => [
            // relation method which have to exists
            'method'  => 'aclResources',
            // column(s) to find specific #sync_relations items below
            'columns' => 'code',
            // delete items if not listed here (default: false)
            'delete'  => false,
        ],
    ],
    // data rows itself
    'data'      => [
        [
            'is_enabled'    => true,
            'event_trigger' => 'auto',
            'name'          => 'New Trader',
            'subject'       => '{{ $user->name }}, willkommen als Händler in {{ config("app.name") }}',
            'event_code'    => NotificationEvent::EVENT_CODE_ACL_GROUP_ATTACHED_USERS,
            'event_data'    => [
                'acl_group' => 'Traders',
            ],
            'content'       => '',
            'description'   => 'User becomes new AclGroup "Trader".',
        ],
        [
            'is_enabled'    => false,
            'event_trigger' => 'auto',
            'name'          => 'New ACL Group',
            'subject'       => '{{ $user->name }}, du wurdest Gruppen neu zugewiesen in {{ config("app.name") }}',
            'event_code'    => NotificationEvent::EVENT_CODE_ACL_GROUP_ATTACHED_USERS,
            'event_data'    => [
                'acl_group' => '*',
            ],
            'content'       => '',
            'description'   => 'User becomes new AclGroup(s)',
        ],
        [
            'is_enabled'      => true,
            'event_trigger'   => 'auto',
            'name'            => 'New ACL Group',
            'subject'         => '{{ $user->name }}, du wurdest Gruppen neu zugewiesen in {{ config("app.name") }}',
            'event_code'      => NotificationEvent::EVENT_CODE_ACL_GROUP_ATTACHED_USERS,
            // 'force_channel'   => 'telegram',
            'content'         => '',
            // 'content_data'  => '',
            'event_data'      => [
                'acl_group' => '*',
                'buttons'   => 'website_link',

            ],
            'description'     => 'User becomes new AclGroup(s)',
            '#sync_relations' => [
                'notification_concerns' => [
                    'market_user_assigned_to_acl_group',
                ],
                'acl_resources'         => [],
                'users'                 => [],
            ],
        ],
        [
            'is_enabled'      => true,
            'event_trigger'   => 'auto',
            'name'            => 'New Trader',
            'subject'         => '{{ $user->name }}, du wurdest Gruppen neu zugewiesen in {{ config("app.name") }}',
            'event_code'      => NotificationEvent::EVENT_CODE_ACL_GROUP_ATTACHED_USERS,
            // 'force_channel'   => 'telegram',
            'content'         => '',
            // 'content_data'  => '',
            'event_data'      => [
                'acl_group' => 'Traders',
                'buttons'   => 'website_link',
            ],
            'description'     => 'User becomes new AclGroup(s)',
            '#sync_relations' => [
                'notification_concerns' => [
                    'market_user_assigned_to_trader',
                ],
                'acl_resources'         => [],
                'users'                 => [],
            ],
        ],
    ],
];
