<?php

return [
    'name' => 'FreelancerOPS',
    'manifest' => [
        'name' => env('APP_NAME', 'FreelancerOPS'),
        'short_name' => 'FreelancerOPS',
        'start_url' => '/',
        'background_color' => '#e48b0c',
        'theme_color' => '#2469a7',
        'display' => 'standalone',
        'orientation'=> 'landscape',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/images/maskable_icon7272.png',
                'purpose' => 'any maskable'
            ],
            '96x96' => [
                'path' => '/images/maskable_icon9696.png',
                'purpose' => 'any maskable'
            ],
            '128x128' => [
                'path' => '/images/maskable_icon128128.png',
                'purpose' => 'any maskable'
            ],
            '144x144' => [
                'path' => '/images/maskable_icon144144.png',
                'purpose' => 'any maskable'
            ],
            '152x152' => [
                'path' => '/images/maskable_icon152152.png',
                'purpose' => 'any maskable'
            ],
            '192x192' => [
                'path' => '/images/maskable_icon192192.png',
                'purpose' => 'any maskable'
            ],
            '384x384' => [
                'path' => '/images/maskable_icon384384.png',
                'purpose' => 'any maskable'
            ],
            '512x512' => [
                'path' => '/images/maskable_icon512512.png',
                'purpose' => 'any maskable'
            ],
        ],
        'splash' => [
            '640x1136' => '/images/icons/splash-640x1136.png',
            '750x1334' => '/images/icons/splash-750x1334.png',
            '828x1792' => '/images/icons/splash-828x1792.png',
            '1125x2436' => '/images/icons/splash-1125x2436.png',
            '1242x2208' => '/images/icons/splash-1242x2208.png',
            '1242x2688' => '/images/icons/splash-1242x2688.png',
            '1536x2048' => '/images/icons/splash-1536x2048.png',
            '1668x2224' => '/images/icons/splash-1668x2224.png',
            '1668x2388' => '/images/icons/splash-1668x2388.png',
            '2048x2732' => '/images/icons/splash-2048x2732.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Shortcut Link 1',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/shortcutlink1',
                'icons' => [
                    "src" => "/images/maskable_icon7272.png",
                    "purpose" => "any maskable"
                ]
            ],
            [
                'name' => 'Shortcut Link 2',
                'description' => 'Shortcut Link 2 Description',
                'url' => '/shortcutlink2',
              'icons' => [
                    "src" => "/images/maskable_icon.png",
                    "purpose" => "any"
                ]
            ]
        ],
        'custom' => []
    ]
];
