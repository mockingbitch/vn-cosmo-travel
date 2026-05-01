<?php

return [
    'supported' => [
        'en' => [
            'label' => 'EN',
            'name' => 'English',
        ],
        'vi' => [
            'label' => 'VI',
            'name' => 'Tiếng Việt',
        ],
    ],

    'default' => env('APP_LOCALE', 'en'),
    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),
];

