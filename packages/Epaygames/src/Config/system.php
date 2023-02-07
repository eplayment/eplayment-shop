<?php

return [
    [
        'key'    => 'sales.paymentmethods.epaygames',
        'name'   => 'epaygames::app.admin.system.epaygames-payment',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'failed_payment_url',
                'info'          => 'epaygames::app.admin.system.failed-payment-info',
                'title'         => 'epaygames::app.admin.system.failed-payment-url',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'success_payment_url',
                'info'          => 'epaygames::app.admin.system.success-payment-info',
                'title'         => 'epaygames::app.admin.system.success-payment-url',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'sandbox',
                'title'         => 'admin::app.admin.system.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'    => 'sort',
                'title'   => 'admin::app.admin.system.sort_order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ], [
                        'title' => '5',
                        'value' => 5,
                    ],
                ],
            ]
        ]
    ]
];