<?php

use Modules\Market\app\Models\PaymentMethod;

return [
    // class of eloquent model
    'model'                => PaymentMethod::class,
    // update data if exists and data differ (default false)
    'update'               => true,
    // if update true only: don't update this fields
    'ignore_update_fields' => [
        'code',
    ],
    // columns to check if data already exists (AND WHERE)
    'uniques'              => ['code'],
    // data rows itself
    'data'                 => [
        [
            'name'        => 'Free',
            'code'        => 'free',
            'description' => 'kostenlos',
        ],
        [
            'name'        => 'Cash',
            'code'        => 'cash',
            'description' => 'Barzahlung',
        ],
        [
            'name'        => 'Bank Transfer',
            'code'        => 'bank_transfer',
            'description' => 'Überweisung',
        ],
        [
            'name'        => 'Prepayment',
            'code'        => 'prepayment',
            'description' => 'Vorkasse',
        ],
        [
            'name'        => 'Purchase On Invoice',
            'code'        => 'purchase_on_invoice',
            'description' => 'Rechnung',
        ],
        [
            'name'        => 'Cash On Delivery',
            'code'        => 'cash_on_delivery',
            'description' => 'Nachnahme',
        ],
        [
            'name'        => 'Offer',
            'code'        => 'offer',
            'description' => 'Auf Angebot',
        ],
        [
            'name'        => 'Exchange of Goods',
            'code'        => 'exchange_of_goods',
            'description' => 'Warentausch',
        ],
        [
            'name'        => 'Credit Card',
            'code'        => 'credit_card',
            'description' => 'Kreditkarte',
        ],
        [
            'name'        => 'Electronic Bank Transfer',
            'code'        => 'electronic_bank_transfer',
            'description' => 'Elektronische Banküberweisung',
        ],
        [
            'name'        => 'Klarna',
            'code'        => 'klarna',
            'description' => 'Anbieter Klarna',
        ],
        [
            'name'        => 'Telegram',
            'code'        => 'telegram',
            'description' => 'Telegram',
        ],
    ],
];

