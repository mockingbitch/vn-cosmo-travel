<?php

return [
    'supported' => [
        'en' => [
            'label' => 'EN',
            'name' => 'English',
        ],
    ],

    'default' => env('APP_LOCALE', 'en'),
    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),
];

