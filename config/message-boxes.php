<?php
return [
    'product'            => [
        'data-table' => [
            // 'edit'=> [ // edit box
            //     title=> 'Edit Product',
            //     content=> '...',
            //     actions=> [
            //         'cancel',
            //         'deleteItem',
            //     ],
            // ],
            // delete box
            'delete' => [
                'title'   => 'Delete Product',
                'content' => 'ask_delete_product',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'deleteItem',
                ],
            ],
        ],
        'default'      => [
            'rating' => [
                'title'        => 'Submit Product Rating',
                'message-box-template' => 'forms.product-rating',//view('forms.product-rating')->render(),
//                'fetch-content' => route('get.form.rating.product'),
                'fetch-content' => '/get-form-rating/product',
                // constant names from defaultActions[] or closure
                'actions'      => [
                    'cancel',
                    'accept-rating',
                ],
            ],
        ],
    ],
    'offer'              => [
        'data-table' => [
            // message box > delete
            'delete' => [
                'title'   => 'Delete Offer',
                'content' => 'ask_delete_offer',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'deleteItem',
                ],
            ],
        ],
        'form'      => [
            'create-offer-binding' => [
                'title'   => 'Create Offer Binding',
                'content' => 'ask_create_offer',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'create-offer-binding',
                ],
            ],
            'offer-suspend'       => [
                'title'   => 'Suspend',
                'content' => 'ask_suspend_offer',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'offer-suspend',
                ],
            ],
            'reject-offer'        => [
                'title'   => 'Reject Offer',
                'content' => 'ask_reject_offer',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'reject-offer',
                ],
            ],
            're-offer'            => [
                'title'   => 'Create New Offer',
                'content' => 'ask_re_offer',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    're-offer',
                ],
            ],
            'accept-offer'        => [
                'title'   => 'Accept Offer',
                'content' => 'ask_accept_offer',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'accept-offer',
                ],
            ],
        ],
    ],
    //    'shopping-cart-item' => [
    //        'data-table' => [
    //            // message box > delete
    //            'delete' => [
    //                'title'   => 'Delete Cart Item',
    //                'content' => 'ask_delete_cart_item',
    //                // constant names from defaultActions[] or closure
    //                'actions' => [
    //                    'cancel',
    //                    'deleteItem',
    //                ],
    //            ],
    //        ],
    //
    //    ],
];
