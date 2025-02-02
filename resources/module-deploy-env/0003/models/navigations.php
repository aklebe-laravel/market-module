<?php

use Modules\Acl\app\Models\AclResource;

return [
    // class of eloquent model
    'model'     => \Modules\WebsiteBase\app\Models\Navigation::class,
    // update data if exists and data differ (default false)
    'update'    => true,
    // columns to check if data already exists (AND WHERE)
    'uniques'   => ['code'],
    // relations to update/create
    'relations' => [
        'res' => [
            // relation method which have to exists
            'method'  => 'parent',
            // column(s) to find specific #sync_relations items below
            'columns' => 'code',
            // delete items if not listed here (default: false)
            'delete'  => false,
        ],
    ],
    // data rows itself
    'data'      => [
        [
            // {{Categories}} dynamic generated navigation depends on categories collection
            'label'      => '{{Categories}}',
            'code'       => 'Categories-Menu-L1',
            'icon_class' => 'bi bi-shop',
            'position'   => 910,
        ],
        [
            'label'           => 'Market Place',
            'code'            => 'Admin-Market-Place-Menu-L2',
            'route'           => 'manage-data-all',
            'route_params'    => ['Product'],
            'acl_resources'   => [AclResource::RES_MANAGE_CONTENT],
            'icon_class'      => 'bi bi-bank',
            'position'        => 7000,
            '#sync_relations' => [
                'res' => [
                    'Admin-Menu-L1',
                ],
            ],
        ],
        [
            'label'           => 'Products',
            'code'            => 'Admin-Market-Place-Products-L3',
            'route'           => 'manage-data-all',
            'route_params'    => ['Product'],
            'icon_class'      => 'bi bi-basket3',
            'position'        => 1000,
            '#sync_relations' => [
                'res' => [
                    'Admin-Market-Place-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Categories',
            'code'            => 'Admin-Market-Place-Categories-L3',
            'route'           => 'manage-data-all',
            'route_params'    => ['Category'],
            'icon_class'      => 'bi bi-diagram-3',
            'position'        => 1100,
            '#sync_relations' => [
                'res' => [
                    'Admin-Market-Place-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Media',
            'code'            => 'Admin-Market-Place-Media-L3',
            'route'           => 'manage-data-all',
            'route_params'    => ['MediaItem'],
            'icon_class'      => 'bi bi-image',
            'position'        => 1200,
            '#sync_relations' => [
                'res' => [
                    'Admin-Market-Place-Menu-L2',
                ],
            ],
        ],
        [
            'label'         => 'My Market',
            'code'          => 'My-Market-Menu-L1',
            'route'         => 'content-pages-overview',
            'icon_class'    => 'bi bi-bank',
            'position'      => 3000,
            'acl_resources' => [AclResource::RES_TRADER],
        ],
        [
            'label'           => 'Trading',
            'code'            => 'Trading-Menu-L2',
            'route'           => 'shopping-cart',
            'icon_class'      => 'bi bi-coin',
            'position'        => 1000,
            '#sync_relations' => [
                'res' => [
                    'My-Market-Menu-L1',
                ],
            ],
        ],
        [
            'label'           => 'Shopping Cart',
            'code'            => 'Trading-Cart-Menu-L3',
            'route'           => 'shopping-cart',
            'icon_class'      => 'bi bi-cart',
            'position'        => 1000,
            '#sync_relations' => [
                'res' => [
                    'Trading-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Potential Offers',
            'code'            => 'Trading-Potential-Offers-Menu-L3',
            'route'           => 'offer.potential',
            'icon_class'      => 'bi bi-cash',
            'position'        => 1100,
            '#sync_relations' => [
                'res' => [
                    'Trading-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Offer Overview',
            'code'            => 'Trading-Offer-Overview-Menu-L3',
            'route'           => 'manage-data',
            'route_params'    => ['modelName' => 'Offer'],
            'icon_class'      => 'bi bi-cash-stack',
            'position'        => 1200,
            '#sync_relations' => [
                'res' => [
                    'Trading-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => '', // separator
            'code'            => 'My-Market-separator-1-Menu-L2',
            'position'        => 1500,
            '#sync_relations' => [
                'res' => [
                    'Trading-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Trader Wait List',
            'code'            => 'Trading-Trader-Wait-List-Menu-L3',
            'route'           => 'manage-data',
            'route_params'    => ['modelName' => 'TraderAspirant.User'],
            'position'        => 2000,
            'icon_class'      => 'bi bi-person-plus',
            '#sync_relations' => [
                'res' => [
                    'Trading-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Trader List',
            'code'            => 'Trading-Trader-List-Menu-L3',
            'route'           => 'manage-data',
            'route_params'    => ['modelName' => 'Trader.User'],
            'position'        => 2100,
            'icon_class'      => 'bi bi-person-check',
            '#sync_relations' => [
                'res' => [
                    'Trading-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => '',// separator
            'code'            => 'My-Market-separator-2-Menu-L2',
            'position'        => 2500,
            '#sync_relations' => [
                'res' => [
                    'Trading-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'My Shop',
            'code'            => 'Market-Shop-Menu-L2',
            'icon_class'      => 'bi bi-shop-window',
            'position'        => 1200,
            '#sync_relations' => [
                'res' => [
                    'My-Market-Menu-L1',
                ],
            ],
        ],
        [
            'label'           => 'Manage Products',
            'code'            => 'Market-Shop-Products-Menu-L3',
            'route'           => 'manage-data',
            'route_params'    => ['modelName' => 'Product'],
            'icon_class'      => 'bi bi-basket3',
            'position'        => 1200,
            '#sync_relations' => [
                'res' => [
                    'Market-Shop-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Manage MediaItems',
            'code'            => 'Market-Shop-MediaItems-Menu-L3',
            'route'           => 'manage-data',
            'route_params'    => ['modelName' => 'MediaItem'],
            'icon_class'      => 'bi bi-image',
            'position'        => 1100,
            '#sync_relations' => [
                'res' => [
                    'Market-Shop-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => 'Manage Addresses',
            'code'            => 'Market-Shop-Addresses-Menu-L3',
            'route'           => 'manage-data',
            'route_params'    => ['modelName' => 'Address'],
            'icon_class'      => 'bi bi-card-text',
            'position'        => 1900,
            '#sync_relations' => [
                'res' => [
                    'Market-Shop-Menu-L2',
                ],
            ],
        ],
        [
            'label'           => '',// separator
            'code'            => 'My-Market-separator-3-Menu-L2',
            'position'        => 9800,
            '#sync_relations' => [
                'res' => [
                    'My-Market-Menu-L1',
                ],
            ],
        ],
        [
            'label'           => 'UserProfile',
            'code'            => 'Market-UserProfile-Menu-L2',
            'route'           => 'user-profile',
            'icon_class'      => 'bi bi-person-circle',
            'position'        => 9900,
            '#sync_relations' => [
                'res' => [
                    'My-Market-Menu-L1',
                ],
            ],
        ],
    ],
];
