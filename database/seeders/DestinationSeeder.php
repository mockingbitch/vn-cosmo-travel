<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // North
            ['name_en' => 'Hanoi', 'name_vi' => 'Hà Nội', 'region' => 'north', 'description' => 'Old Quarter, street food, culture and day trips to nearby icons.'],
            ['name_en' => 'Ha Long Bay', 'name_vi' => 'Vịnh Hạ Long', 'region' => 'north', 'description' => 'Cruises, limestone karsts and one of Vietnam’s most iconic coastlines.'],
            ['name_en' => 'Ninh Binh', 'name_vi' => 'Ninh Bình', 'region' => 'north', 'description' => 'Trang An, Tam Coc, karst rivers and easy trips from the capital.'],
            ['name_en' => 'Sapa', 'name_vi' => 'Sapa', 'region' => 'north', 'description' => 'Rice terraces, homestays and highland treks.'],
            ['name_en' => 'Lao Cai', 'name_vi' => 'Lào Cai', 'region' => 'north', 'description' => 'Highland city, night trains and a gateway to Sapa and the Chinese border.'],
            ['name_en' => 'Ha Giang', 'name_vi' => 'Hà Giang', 'region' => 'north', 'description' => 'Epic loop roads, border passes and ethnic highland culture.'],
            ['name_en' => 'Mai Chau', 'name_vi' => 'Mai Châu', 'region' => 'north', 'description' => 'Valley stilt houses, cycling and gentle hill scenery.'],
            ['name_en' => 'Pu Luong', 'name_vi' => 'Pù Luông', 'region' => 'north', 'description' => 'Quiet nature reserve with rice fields and small villages.'],
            ['name_en' => 'Cat Ba', 'name_vi' => 'Cát Bà', 'region' => 'north', 'description' => 'Lan Ha access, beaches and a gateway to the bay.'],
            ['name_en' => 'Cao Bang', 'name_vi' => 'Cao Bằng', 'region' => 'north', 'description' => 'Remote northern province, waterfalls and highland border scenery.'],
            ['name_en' => 'Ba Be', 'name_vi' => 'Ba Bể', 'region' => 'north', 'description' => 'National park, lakes, limestone forests and village stays.'],
            ['name_en' => 'Bac Ha', 'name_vi' => 'Bắc Hà', 'region' => 'north', 'description' => 'Colorful highland market town near Lao Cai.'],
            ['name_en' => 'Dien Bien Phu', 'name_vi' => 'Điện Biên Phủ', 'region' => 'north', 'description' => 'Valley city with historic battlefield and gateway to the far northwest.'],
            ['name_en' => 'Mu Cang Chai', 'name_vi' => 'Mù Cang Chải', 'region' => 'north', 'description' => 'Famous terraced fields and seasonal harvest photography.'],
            ['name_en' => 'Moc Chau', 'name_vi' => 'Mộc Châu', 'region' => 'north', 'description' => 'Valleys, tea hills and a cool escape from the capital.'],
            ['name_en' => 'Son La', 'name_vi' => 'Sơn La', 'region' => 'north', 'description' => 'Hydro lake views and the road toward Dien Bien.'],
            ['name_en' => 'Lai Chau', 'name_vi' => 'Lai Châu', 'region' => 'north', 'description' => 'Northwestern mountains, passes and small hill towns.'],

            // North central coast
            ['name_en' => 'Phong Nha-Ke Bang', 'name_vi' => 'Phong Nha - Kẻ Bàng', 'region' => 'north_central', 'description' => 'World-class caves, jungle treks and adventure gateway.'],
            ['name_en' => 'Quang Tri', 'name_vi' => 'Quảng Trị', 'region' => 'north_central', 'description' => 'DMZ history, coastal road stops toward Hue.'],
            ['name_en' => 'Dong Hoi', 'name_vi' => 'Đồng Hới', 'region' => 'north_central', 'description' => 'Seaside city and jump-off to Phong Nha and the coast.'],
            ['name_en' => 'Ha Tinh', 'name_vi' => 'Hà Tĩnh', 'region' => 'north_central', 'description' => 'North central coast, hill roads toward Laos and long beaches north of Hue.'],
            ['name_en' => 'Thanh Hoa', 'name_vi' => 'Thanh Hóa', 'region' => 'north_central', 'description' => 'Ninh Binh’s northern neighbor, gateway to Pu Luong and the coast.'],
            ['name_en' => 'Vinh', 'name_vi' => 'Vinh', 'region' => 'north_central', 'description' => 'Largest city in north-central Vietnam; hub for the Ho Chi Minh Trail and beaches.'],

            // Central coast & heritage
            ['name_en' => 'Da Nang', 'name_vi' => 'Đà Nẵng', 'region' => 'central', 'description' => 'Coastal city and hub for beaches, Hoi An and the mountains.'],
            ['name_en' => 'Hoi An', 'name_vi' => 'Hội An', 'region' => 'central', 'description' => 'UNESCO old town, lanterns, tailors and riverside food.'],
            ['name_en' => 'Hue', 'name_vi' => 'Huế', 'region' => 'central', 'description' => 'Imperial city, tombs, Perfume River and central heritage.'],
            ['name_en' => 'My Son', 'name_vi' => 'Mỹ Sơn', 'region' => 'central', 'description' => 'Cham temple ruins, easy to pair with Hoi An or Danang.'],
            ['name_en' => 'Quy Nhon', 'name_vi' => 'Quy Nhơn', 'region' => 'central', 'description' => 'Laid-back beach city with Cham heritage nearby.'],
            ['name_en' => 'Nha Trang', 'name_vi' => 'Nha Trang', 'region' => 'central', 'description' => 'Beach resorts, islands and diving.'],
            ['name_en' => 'Mui Ne', 'name_vi' => 'Mũi Né', 'region' => 'central', 'description' => 'Sand dunes, kitesurfing and beach resort strip.'],
            ['name_en' => 'Phan Thiet', 'name_vi' => 'Phan Thiết', 'region' => 'central', 'description' => 'Coast and fishing port near white-sand resort areas.'],
            ['name_en' => 'Tuy Hoa', 'name_vi' => 'Tuy Hòa', 'region' => 'central', 'description' => 'Beach, river mouth and a quieter central coast base.'],
            ['name_en' => 'Phu Yen', 'name_vi' => 'Phú Yên', 'region' => 'central', 'description' => 'Airy beaches, lighthouses and a relaxed stop between hubs.'],
            ['name_en' => 'Ninh Thuan', 'name_vi' => 'Ninh Thuận', 'region' => 'central', 'description' => 'Mui Dinh, vineyards and a dry, sunny central coast region.'],
            ['name_en' => 'Lang Co', 'name_vi' => 'Lăng Cô', 'region' => 'central', 'description' => 'Lagoon bay and scenic stops between Danang and Hue.'],
            ['name_en' => 'Cam Ranh', 'name_vi' => 'Cam Ranh', 'region' => 'central', 'description' => 'Long white beaches and a growing resort corridor.'],
            ['name_en' => 'Binh Dinh', 'name_vi' => 'Bình Định', 'region' => 'central', 'description' => 'Qui Nhon hinterland, Champa heritage and the central highlands fringe.'],

            // Central Highlands
            ['name_en' => 'Da Lat', 'name_vi' => 'Đà Lạt', 'region' => 'highlands', 'description' => 'Highland climate, lakes, coffee and cool-season escapes.'],
            ['name_en' => 'Buon Ma Thuot', 'name_vi' => 'Buôn Ma Thuột', 'region' => 'highlands', 'description' => 'Central Highland coffee country and easy nature loops.'],
            ['name_en' => 'Kon Tum', 'name_vi' => 'Kon Tum', 'region' => 'highlands', 'description' => 'Highland city, wooden churches and ethnic homestay routes.'],
            ['name_en' => 'Pleiku', 'name_vi' => 'Pleiku', 'region' => 'highlands', 'description' => 'Central Highland gateway to lakes, forests and Bahnar culture.'],

            // Southeast
            ['name_en' => 'Vung Tau', 'name_vi' => 'Vũng Tàu', 'region' => 'southeast', 'description' => 'Weekend beaches and lighthouse views near Ho Chi Minh City.'],
            ['name_en' => 'Ho Chi Minh City', 'name_vi' => 'Thành phố Hồ Chí Minh', 'region' => 'southeast', 'description' => 'Dynamic city vibe, history tours and Mekong delta gateways.'],
            ['name_en' => 'Con Dao', 'name_vi' => 'Côn Đảo', 'region' => 'southeast', 'description' => 'Secluded archipelago, history, beaches and diving.'],

            // Mekong
            ['name_en' => 'Can Tho', 'name_vi' => 'Cần Thơ', 'region' => 'mekong', 'description' => 'Big Mekong city, floating markets and easy delta routes.'],
            ['name_en' => 'Ben Tre', 'name_vi' => 'Bến Tre', 'region' => 'mekong', 'description' => 'Coconut country, sampan canals and fruit orchards.'],
            ['name_en' => 'Tien Giang', 'name_vi' => 'Tiền Giang', 'region' => 'mekong', 'description' => 'Mekong orchards, crafts villages and short trips from the city.'],
            ['name_en' => 'An Giang', 'name_vi' => 'An Giang', 'region' => 'mekong', 'description' => 'Sam mountain, Chau Doc and the upper Mekong flats.'],
            ['name_en' => 'Tra Vinh', 'name_vi' => 'Trà Vinh', 'region' => 'mekong', 'description' => 'Khmer-influenced towns, pagodas and slow-paced delta roads.'],
            ['name_en' => 'Soc Trang', 'name_vi' => 'Sóc Trăng', 'region' => 'mekong', 'description' => 'Khmer pagodas, floating life and the lower Mekong flatlands.'],
            ['name_en' => 'Phu Quoc', 'name_vi' => 'Phú Quốc', 'region' => 'mekong', 'description' => 'Island breaks, night markets, seafood and family-friendly resorts.'],
        ];

        foreach ($rows as $row) {
            $nameEn = $row['name_en'];
            $slug = Str::slug($nameEn);
            if ($slug === '') {
                continue;
            }
            Destination::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name_en' => $nameEn,
                    'name_vi' => $row['name_vi'],
                    'description' => $row['description'],
                    'region' => $row['region'],
                ],
            );
        }
    }
}
