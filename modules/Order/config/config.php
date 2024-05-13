<?php

return [
    'name' => 'Order',

    'drivers' => [
        'zarinpal' => [
            'label' => 'زرین پال',
            'image' => asset('assets/zarinpal.png'),
            'options' => [
                'transaction_id' => 'Authority'
            ]
        ]
    ]
];
