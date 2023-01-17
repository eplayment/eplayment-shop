<?php

return [
    'gateway' => [
        'host' => env('EPAYGAMES_HOST', ''),
        'token' => env('EPAYGAMES_TOKEN', ''),
        'signature_key' => env('EPAYGAMES_SIGNATURE_KEY', ''),
    ]
];
