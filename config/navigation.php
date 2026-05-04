<?php

return [
    /*
    | Primary links (order = top bar left to right). Types: link, mega, dropdown.
    | Dropdown panels are listed under `dropdown_panels` keyed by `panel`.
    */
    'primary' => [
        [
            'id' => 'halong_bay',
            'label_key' => 'nav.primary.halong_bay',
            'type' => 'dropdown',
            'panel' => 'halong',
        ],
        [
            'id' => 'northern_trip',
            'label_key' => 'nav.primary.northern_trip',
            'type' => 'dropdown',
            'panel' => 'northern',
        ],
        [
            'id' => 'central_southern',
            'label_key' => 'nav.primary.central_southern',
            'type' => 'dropdown',
            'panel' => 'central_south',
        ],
        [
            'id' => 'package',
            'label_key' => 'nav.primary.package_tour',
            'type' => 'link',
            'tour_params' => ['duration' => '4-7'],
        ],
        [
            'id' => 'other_service',
            'label_key' => 'nav.primary.other_service',
            'type' => 'dropdown',
            'panel' => 'other_service',
        ],
        [
            'id' => 'about',
            'label_key' => 'nav.primary.about_us',
            'type' => 'link',
            'route' => 'about',
            'params' => [],
        ],
    ],

    /*
    | Simple dropdown menus (desktop hover panel + mobile accordion).
    | Each row: label_key + tour_params and/or route/hash/external_url (same rules as primary links).
    */
    'dropdown_panels' => [
        'halong' => [
            ['label_key' => 'nav.sub.halong_day1', 'tour_params' => ['destination' => 'ha-long-bay', 'duration' => '1-3']],
            ['label_key' => 'nav.sub.halong_2d1n', 'tour_params' => ['destination' => 'ha-long-bay', 'duration' => '1-3']],
            ['label_key' => 'nav.sub.halong_3d2n', 'tour_params' => ['destination' => 'ha-long-bay', 'duration' => '4-7']],
            ['label_key' => 'nav.sub.lan_ha_bay', 'tour_params' => ['destination' => 'cat-ba']],
            ['label_key' => 'nav.sub.bai_tu_long', 'tour_params' => ['destination' => 'ha-long-bay']],
        ],
        'northern' => [
            ['label_key' => 'nav.sub.sapa', 'tour_params' => ['destination' => 'sapa']],
            ['label_key' => 'nav.sub.ha_giang_loop', 'tour_params' => ['destination' => 'ha-giang']],
            ['label_key' => 'nav.sub.mai_chau', 'tour_params' => ['destination' => 'mai-chau']],
            ['label_key' => 'nav.sub.mu_cang_chai', 'tour_params' => ['destination' => 'mu-cang-chai']],
            ['label_key' => 'nav.sub.ba_be_bac_kan', 'tour_params' => ['destination' => 'ba-be']],
            ['label_key' => 'nav.sub.ban_gioc', 'tour_params' => ['destination' => 'cao-bang']],
        ],
        'central_south' => [
            ['label_key' => 'nav.sub.da_nang', 'tour_params' => ['destination' => 'da-nang']],
            ['label_key' => 'nav.sub.hue', 'tour_params' => ['destination' => 'hue']],
            ['label_key' => 'nav.sub.hoi_an', 'tour_params' => ['destination' => 'hoi-an']],
            ['label_key' => 'nav.sub.ho_chi_minh', 'tour_params' => ['destination' => 'ho-chi-minh-city']],
            ['label_key' => 'nav.sub.mekong_delta', 'tour_params' => ['destination' => 'can-tho']],
            ['label_key' => 'nav.sub.nha_trang', 'tour_params' => ['destination' => 'nha-trang']],
            ['label_key' => 'nav.sub.da_lat', 'tour_params' => ['destination' => 'da-lat']],
            ['label_key' => 'nav.sub.mui_ne', 'tour_params' => ['destination' => 'mui-ne']],
            ['label_key' => 'nav.sub.phu_quoc', 'tour_params' => ['destination' => 'phu-quoc']],
        ],
        'other_service' => [
            ['label_key' => 'nav.sub.airport_taxi', 'route' => 'airport-taxi', 'params' => []],
            ['label_key' => 'nav.sub.visa_service', 'route' => 'visa-service', 'params' => []],
            ['label_key' => 'nav.sub.bus_flight_train', 'route' => 'bus-flight-train-ticket', 'params' => []],
            ['label_key' => 'nav.sub.sim_card', 'route' => 'sim-card', 'params' => []],
        ],
    ],

    /*
    | Legacy mega menu (optional). Leave empty when not used.
    */
    'mega_rows' => [],

    'cruise' => [],

    'mega_featured' => [],
];
