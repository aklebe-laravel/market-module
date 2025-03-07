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
                'cmd'     => 'models',
                'sources' => [
                    'payment-methods.php',
                    'shipping-methods.php',
                ],
            ],
            [
                'cmd'     => 'models',
                'sources' => [
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
        '0010' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'core-config-from-0001.php',
                    'core-config-from-0008.php',
                ],
            ],
        ],
        '0011' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'model-attribute-assignments.php',
                ],
            ],
        ],
        '0012' => [
            [
                'cmd'     => 'models',
                'sources' => [
                    'model-attribute-assignments.php',
                ],
            ],
        ],
    ],

];
