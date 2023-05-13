<?php

return [
    'name' => 'FreelancerOPS',
    'manifest' => [
        'name' => env('APP_NAME', 'FreelancerOPS'),
        'short_name' => 'FreelancerOPS',
        'start_url' => '/',
        'background_color' => '#e48b0c',
        'theme_color' => '#2469a7',
        "display" => "browser",
        'orientation' => 'landscape',
        'status_bar' => 'black',
        'icons' => [
            '72x72' => [
                'path' => '/images/maskable_icon7272.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/images/maskable_icon9696.png',
                'purpose' => 'maskable'
            ],
            '128x128' => [
                'path' => '/images/maskable_icon128128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/images/maskable_icon144144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/images/maskable_icon152152.png',
                'purpose' => 'maskable'
            ],
            '192x192' => [
                'path' => '/images/maskable_icon192192.png',
                'purpose' => 'maskable'
            ],
            '384x384' => [
                'path' => '/images/maskable_icon384384.png',
                'purpose' => 'maskable'
            ],
            '512x512' => [
                'path' => '/images/maskable_icon512512.png',
                'purpose' => 'any'
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
                'name' => 'Login',
                'description' => 'Menuju Login',
                'url' => '/',
                'icons' => [
                    'src' => "/images/maskable_icon512512.png",
                    'purpose' => 'any',
                    "sizes" => "96x96",
                ]
            ]
        ],
        'custom' => [
            'lang' => 'id',
            "scope" => "/",
            "categories" => ["business", "productivity", "utilities"],
            "description" => "FreelancerOPS membantu anda sebagai freelancer untuk mencari pekerjaan ataupun magang!",
            "dir" => "ltr",
            "prefer_related_applications" => false,
            "display_override" => [
                "window-control-overlay",
                "standalone",
                "browser"
            ],
            "screenshots" => [
                [
                    "src" => "/images/dashboard.png",
                    "sizes" => "1366x768",
                    "type" => "image/png",
                    "platform" => "wide",
                ],
                [
                    "src" => "/images/listmodul.png",
                    "sizes" => "1366x768",
                    "type" => "image/png",
                    "platform" => "wide",
                ],
                [
                    "src" => "/images/tambahmodul.png",
                    "sizes" => "1366x768",
                    "type" => "image/png",
                    "platform" => "wide",
                ]
            ],
            "related_applications" => [
                [
                    "platform" => "webapp",
                    "url" => "https://www.freelancer.co.id/"
                ],
                [
                    "platform" => "webapp",
                    "url" => "https://www.sribu.com/"
                ]
            ],
        ]
    ]
];
