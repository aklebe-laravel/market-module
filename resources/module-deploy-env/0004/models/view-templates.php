<?php

use Modules\WebsiteBase\app\Models\ViewTemplate;

return [
    // class of eloquent model
    'model'     => ViewTemplate::class,
    // update data if exists and data differ (default false)
    'update'    => true,
    // columns to check if data already exists (AND WHERE)
    'uniques'   => ['code'],
    // relations to update/create
    'relations' => [],
    // data rows itself
    'data'      => [
        [
            'is_enabled'        => true,
            'code'              => 'email_market_offer_created',
            'content'           => '',
            'view_file'         => 'notifications.emails.offers.created-html',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Offer created.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'email_market_offer_rejected',
            'content'           => '',
            'view_file'         => 'notifications.emails.offers.rejected-html',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Offer rejected.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'email_market_offer_completed',
            'content'           => '',
            'view_file'         => 'notifications.emails.offers.completed-html',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Offer completed.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'email_market_user_assigned_to_trader',
            'content'           => '',
            'view_file'         => 'notifications.emails.user-assigned-to-trader',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'User assigned to Trader.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'email_market_user_assigned_to_acl_group',
            'content'           => '',
            'view_file'         => 'notifications.emails.user-assigned-to-acl-group',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'User assigned to acl group.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'email_market_media_item_import',
            'content'           => '',
            'view_file'         => 'notifications.emails.market-media-item-import',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Import completed.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'telegram_market_offer_created',
            'content'           => '',
            'view_file'         => 'notifications.telegram.offers.created-html',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Offer created.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'telegram_market_offer_rejected',
            'content'           => '',
            'view_file'         => 'notifications.telegram.offers.rejected-html',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Offer rejected.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'telegram_market_offer_completed',
            'content'           => '',
            'view_file'         => 'notifications.telegram.offers.completed-html',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Offer completed.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'telegram_market_user_assigned_to_trader',
            'content'           => '',
            'view_file'         => 'notifications.telegram.user-assigned-to-trader',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'User assigned to Trader.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'telegram_market_user_assigned_to_acl_group',
            'content'           => '',
            'view_file'         => 'notifications.telegram.user-assigned-to-acl-group',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'User assigned to acl group.',
        ],
        [
            'is_enabled'        => true,
            'code'              => 'telegram_market_media_item_import',
            'content'           => '',
            'view_file'         => 'notifications.telegram.market-media-item-import',
            'parameter_variant' => ViewTemplate::PARAMETER_VARIANT_DEFAULT,
            'description'       => 'Import completed.',
        ],
    ],
];
