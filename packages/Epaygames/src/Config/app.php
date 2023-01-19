<?php

return [
    'gateway' => [
        'host'          => env('EPAYGAMES_HOST', ''),
        'token'         => env('EPAYGAMES_TOKEN', ''),
        'signature_key' => env('EPAYGAMES_SIGNATURE_KEY', ''),

        'sandbox' => [
            'host'          => env('EPAYGAMES_HOST_SBX', ''),
            'token'         => env('EPAYGAMES_TOKEN_SBX', ''),
            'signature_key' => env('EPAYGAMES_SIGNATURE_KEY_SBX', ''),
        ],        
    ]
];
