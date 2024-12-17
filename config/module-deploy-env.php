<?php

use Modules\Market\app\Models\PaymentMethod;
use Modules\WebsiteBase\app\Models\CoreConfig;

return [

    /*
    |--------------------------------------------------------------------------
    | Config of deployments. Can be updated by adding new identifiers.
    | See module DeployEnv README.md
    |--------------------------------------------------------------------------
    */

    'deployments' => [
        // Identifier to remember this deployment was already done.
        '0001' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'acl-groups.php',
                    'model-attributes.php',
                    'model-attribute-assignments.php',
                ],
            ],
            [
                'cmd'        => 'models',
                // conditions no needed, because 'payments-methods.php' will check every entry by 'models'
                'conditions' => [
                    [
                        'function' => function ($code) {
                            // payment methods have to be empty
                            return (bool) (!PaymentMethod::first());
                        },
                    ],
                ],
                'sources'    => [
                    'payment-methods.php',
                    'shipping-methods.php',
                ],
            ],
            [
                'cmd'        => 'models',
                // conditions no needed, because 'core-config.php' will check every entry by 'models'
                'conditions' => [
                    [
                        'function' => function ($code) {
                            // specific config should not exist
                            return (bool) (!CoreConfig::where('path',
                                'catalog.product.image.width')->first());
                        },
                    ],
                ],
                'sources'    => [
                    'core-config.php',
                ],
            ],
            [
                'cmd'     => 'models',
                'sources' => [
                    'categories.php',
                ],
            ],
            [
                'cmd'     => 'artisan',
                'sources' => [
                    'cache:clear',
                ],
            ],
        ],
        '0003' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'navigations.php',
                ],
            ],
        ],
        '0004' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'view-templates.php',
                    'notification-templates.php',
                    'notification-concerns.php',
                ],
            ],
        ],
        '0005' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'notification-events.php',
                ],
            ],
        ],
        '0007' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'acl-resources.php',
                    'acl-groups.php',
                ],
            ],
        ],
        '0008' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'core-config.php',
                ],
            ],
        ],
        '0009' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'navigations.php',
                ],
            ],
        ],
    ],

];
