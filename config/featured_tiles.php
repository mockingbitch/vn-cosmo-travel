<?php

/**
 * Default content for the homepage "featured" mosaic.
 *
 * Admins override any field per tile in Admin → Settings → Featured tiles;
 * every field left empty falls back to the values below.
 *
 * `slug` is not editable: it is the `?p=` key of the /featured page a tile links
 * to, so links stay valid when an admin renames a tile. `destination_slug` is
 * the fallback used to list tours when a tile has no tours picked yet.
 */
return [
    'tiles' => [
        [
            'eyebrow' => 'Vietnam',
            'slug' => 'hanoi-north',
            'title' => 'Hanoi & North',
            'chips' => ['Hanoi', 'Ha Long Bay', 'Ninh Binh', 'Mai Chau', 'Cat Ba'],
            'description' => "Vietnam's historic capital, emerald karst bays & ancient highland villages — the perfect northern base.",
            'destination_slug' => 'hanoi',
            'image_url' => 'https://images.unsplash.com/photo-1555921015-5532091f6026?auto=format&fit=crop&w=1600&q=80',
        ],
        [
            'eyebrow' => 'Vietnam',
            'slug' => 'sapa-north-west',
            'title' => 'Sapa & North West',
            'chips' => ['Sapa', 'Mu Cang Chai', 'Moc Chau', 'Bac Ha'],
            'description' => 'Terraced rice valleys, hill-tribe markets and cool mountain air.',
            'destination_slug' => 'sapa',
            'image_url' => 'https://images.unsplash.com/photo-1528181304800-259b08848526?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'eyebrow' => 'Vietnam',
            'slug' => 'ha-giang-north-east',
            'title' => 'Ha Giang & North East',
            'chips' => ['Ha Giang', 'Cao Bang', 'Ba Be Lake'],
            'description' => 'The legendary loop: limestone passes, river canyons and remote villages.',
            'destination_slug' => 'ha-giang',
            'image_url' => 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'eyebrow' => 'Vietnam',
            'slug' => 'hue-central-heritage',
            'title' => 'Hue & Central Heritage',
            'chips' => ['Hue', 'Hoi An', 'Da Nang', 'Phong Nha'],
            'description' => 'Imperial citadels, lantern-lit old towns and the world’s largest caves.',
            'destination_slug' => 'hue',
            'image_url' => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'eyebrow' => 'Vietnam',
            'slug' => 'mekong-delta',
            'title' => 'Mekong Delta',
            'chips' => ['Can Tho', 'Ben Tre', 'Tien Giang', 'Tra Vinh'],
            'description' => 'Floating markets, coconut canals and slow river days south of Saigon.',
            'destination_slug' => 'can-tho',
            'image_url' => 'https://images.unsplash.com/photo-1583417267826-aebc4d1542e1?auto=format&fit=crop&w=1600&q=80',
        ],
        [
            'eyebrow' => 'Vietnam',
            'slug' => 'ho-chi-minh-city-south',
            'title' => 'Ho Chi Minh City & South',
            'chips' => ['Ho Chi Minh City', 'Mui Ne', 'Vung Tau', 'Con Dao'],
            'description' => "Saigon's street-food energy, colonial landmarks and southern beach escapes.",
            'destination_slug' => 'ho-chi-minh-city',
            'image_url' => 'https://images.unsplash.com/photo-1533371452382-d45a9da51ad9?auto=format&fit=crop&w=1600&q=80',
        ],
        [
            'eyebrow' => 'Vietnam',
            'slug' => 'phu-quoc',
            'title' => 'Phu Quoc',
            'chips' => ['Duong Dong', 'Sao Beach', 'An Thoi Islands'],
            'description' => 'Island finale: white-sand bays, cable cars and sunset dinners.',
            'destination_slug' => 'phu-quoc',
            'image_url' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=1600&q=80',
        ],
    ],
];
