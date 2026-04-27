<?php

return [
    /*
    | Primary links (order = top bar left to right). Types: link, mega, dropdown.
    | "mega" / "dropdown" use panels defined below.
    */
    'primary' => [
        [
            'id' => 'hanoi_day',
            'label_key' => 'nav.primary.hanoi_day',
            'type' => 'link',
            'tour_params' => ['destination' => 'hanoi', 'duration' => '1-3'],
        ],
        [
            'id' => 'daily',
            'label_key' => 'nav.primary.daily',
            'type' => 'mega',
            'panel' => 'daily',
        ],
        [
            'id' => 'package',
            'label_key' => 'nav.primary.package',
            'type' => 'link',
            'tour_params' => ['duration' => '4-7'],
        ],
        [
            'id' => 'cruise',
            'label_key' => 'nav.primary.cruise',
            'type' => 'dropdown',
            'panel' => 'cruise',
        ],
        [
            'id' => 'about',
            'label_key' => 'nav.primary.about',
            'type' => 'link',
            'route' => 'home',
            'params' => [],
            'hash' => 'contact',
        ],
    ],

    /*
    | Mega "Điểm đến" — cột nhóm; mỗi cột: title_key + thứ tự slug tham chiếu bảng `destinations`.
    | Tên hiển thị: `Destination::localizedName()` (lang destinations.name.{slug}, fallback tên DB).
    */
    'mega_rows' => [
        [
            [
                'title_key' => 'nav.mega.hanoi_north',
                'slugs' => [
                    'hanoi',
                    'ninh-binh',
                    'mai-chau',
                    'pu-luong',
                    'cat-ba',
                ],
            ],
            [
                'title_key' => 'nav.mega.sapa_nw',
                'slugs' => [
                    'sapa',
                    'dien-bien-phu',
                    'mu-cang-chai',
                    'bac-ha',
                    'moc-chau',
                ],
            ],
            [
                'title_key' => 'nav.mega.ha_giang_ne',
                'slugs' => [
                    'ha-giang',
                    'cao-bang',
                    'ba-be',
                    'lao-cai',
                ],
            ],
            [
                'title_key' => 'nav.mega.central',
                'slugs' => [
                    'da-nang',
                    'hue',
                    'hoi-an',
                    'phong-nha-ke-bang',
                    'my-son',
                    'quang-tri',
                ],
            ],
        ],
        [
            [
                'title_key' => 'nav.mega.dalat_highlands',
                'slugs' => [
                    'da-lat',
                    'buon-ma-thuot',
                    'kon-tum',
                    'pleiku',
                ],
            ],
            [
                'title_key' => 'nav.mega.hcm_south',
                'slugs' => [
                    'ho-chi-minh-city',
                    'an-giang',
                    'ben-tre',
                    'can-tho',
                    'tra-vinh',
                    'tien-giang',
                ],
            ],
            [
                'title_key' => 'nav.mega.beaches',
                'slugs' => [
                    'con-dao',
                    'nha-trang',
                    'mui-ne',
                    'vung-tau',
                    'quy-nhon',
                ],
            ],
        ],
    ],

    'cruise' => [
        [
            'label_key' => 'nav.cruise_halong',
            'tour_params' => ['destination' => 'ha-long-bay'],
        ],
        [
            'label_key' => 'nav.cruise_hanoi_short',
            'tour_params' => ['destination' => 'hanoi', 'duration' => '1-3'],
        ],
        [
            'label_key' => 'nav.cruise_mekong',
            'tour_params' => ['destination' => 'ho-chi-minh-city'],
        ],
    ],

    'mega_featured' => [
        'image' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=900&q=80',
        'image_alt_key' => 'nav.mega.featured_alt',
        'title_key' => 'nav.mega.featured_title',
        'subtitle_key' => 'nav.mega.featured_sub',
        'route' => 'tours.index',
        'params' => ['destination' => 'ha-long-bay'],
    ],
];
