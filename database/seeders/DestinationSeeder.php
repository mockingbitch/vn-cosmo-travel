<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // North (Hanoi, Bay, mountains & border)
            ['name' => 'Hanoi', 'region' => 'north', 'description' => 'Old Quarter, street food, culture and day trips to nearby icons.'],
            ['name' => 'Ha Long Bay', 'region' => 'north', 'description' => 'Cruises, limestone karsts and one of Vietnam’s most iconic coastlines.'],
            ['name' => 'Ninh Binh', 'region' => 'north', 'description' => 'Trang An, Tam Coc, karst rivers and easy trips from the capital.'],
            ['name' => 'Sapa', 'region' => 'north', 'description' => 'Rice terraces, homestays and highland treks.'],
            ['name' => 'Lao Cai', 'region' => 'north', 'description' => 'Highland city, night trains and a gateway to Sapa and the Chinese border.'],
            ['name' => 'Ha Giang', 'region' => 'north', 'description' => 'Epic loop roads, border passes and ethnic highland culture.'],
            ['name' => 'Mai Chau', 'region' => 'north', 'description' => 'Valley stilt houses, cycling and gentle hill scenery.'],
            ['name' => 'Pu Luong', 'region' => 'north', 'description' => 'Quiet nature reserve with rice fields and small villages.'],
            ['name' => 'Cat Ba', 'region' => 'north', 'description' => 'Lan Ha access, beaches and a gateway to the bay.'],
            ['name' => 'Cao Bang', 'region' => 'north', 'description' => 'Remote northern province, waterfalls and highland border scenery.'],
            ['name' => 'Ba Be', 'region' => 'north', 'description' => 'National park, lakes, limestone forests and village stays.'],
            ['name' => 'Bac Ha', 'region' => 'north', 'description' => 'Colorful highland market town near Lao Cai.'],
            ['name' => 'Dien Bien Phu', 'region' => 'north', 'description' => 'Valley city with historic battlefield and gateway to the far northwest.'],
            ['name' => 'Mu Cang Chai', 'region' => 'north', 'description' => 'Famous terraced fields and seasonal harvest photography.'],
            ['name' => 'Moc Chau', 'region' => 'north', 'description' => 'Valleys, tea hills and a cool escape from the capital.'],
            ['name' => 'Son La', 'region' => 'north', 'description' => 'Hydro lake views and the road toward Dien Bien.'],
            ['name' => 'Lai Chau', 'region' => 'north', 'description' => 'Northwestern mountains, passes and small hill towns.'],

            // North Central Coast (Bắc Trung Bộ)
            ['name' => 'Phong Nha-Ke Bang', 'region' => 'north_central', 'description' => 'World-class caves, jungle treks and adventure gateway.'],
            ['name' => 'Quang Tri', 'region' => 'north_central', 'description' => 'DMZ history, coastal road stops toward Hue.'],
            ['name' => 'Dong Hoi', 'region' => 'north_central', 'description' => 'Seaside city and jump-off to Phong Nha and the coast.'],
            ['name' => 'Ha Tinh', 'region' => 'north_central', 'description' => 'North central coast, hill roads toward Laos and long beaches north of Hue.'],
            ['name' => 'Thanh Hoa', 'region' => 'north_central', 'description' => 'Ninh Binh’s northern neighbor, gateway to Pu Luong and the coast.'],
            ['name' => 'Vinh', 'region' => 'north_central', 'description' => 'Largest city in north-central Vietnam; hub for the Ho Chi Minh Trail and beaches.'],

            // Central: heritage & south-central coast
            ['name' => 'Da Nang', 'region' => 'central', 'description' => 'Coastal city and hub for beaches, Hoi An and the mountains.'],
            ['name' => 'Hoi An', 'region' => 'central', 'description' => 'UNESCO old town, lanterns, tailors and riverside food.'],
            ['name' => 'Hue', 'region' => 'central', 'description' => 'Imperial city, tombs, Perfume River and central heritage.'],
            ['name' => 'My Son', 'region' => 'central', 'description' => 'Cham temple ruins, easy to pair with Hoi An or Danang.'],
            ['name' => 'Quy Nhon', 'region' => 'central', 'description' => 'Laid-back beach city with Cham heritage nearby.'],
            ['name' => 'Nha Trang', 'region' => 'central', 'description' => 'Beach resorts, islands and diving.'],
            ['name' => 'Mui Ne', 'region' => 'central', 'description' => 'Sand dunes, kitesurfing and beach resort strip.'],
            ['name' => 'Phan Thiet', 'region' => 'central', 'description' => 'Coast and fishing port near white-sand resort areas.'],
            ['name' => 'Tuy Hoa', 'region' => 'central', 'description' => 'Beach, river mouth and a quieter central coast base.'],
            ['name' => 'Phu Yen', 'region' => 'central', 'description' => 'Airy beaches, lighthouses and a relaxed stop between hubs.'],
            ['name' => 'Ninh Thuan', 'region' => 'central', 'description' => 'Mui Dinh, vineyards and a dry, sunny central coast region.'],
            ['name' => 'Lang Co', 'region' => 'central', 'description' => 'Lagoon bay and scenic stops between Danang and Hue.'],
            ['name' => 'Cam Ranh', 'region' => 'central', 'description' => 'Long white beaches and a growing resort corridor.'],
            ['name' => 'Binh Dinh', 'region' => 'central', 'description' => 'Qui Nhon hinterland, Champa heritage and the central highlands fringe.'],

            // Central Highlands
            ['name' => 'Da Lat', 'region' => 'highlands', 'description' => 'Highland climate, lakes, coffee and cool-season escapes.'],
            ['name' => 'Buon Ma Thuot', 'region' => 'highlands', 'description' => 'Central Highland coffee country and easy nature loops.'],
            ['name' => 'Kon Tum', 'region' => 'highlands', 'description' => 'Highland city, wooden churches and ethnic homestay routes.'],
            ['name' => 'Pleiku', 'region' => 'highlands', 'description' => 'Central Highland gateway to lakes, forests and Bahnar culture.'],

            // Southeast: Ho Chi Minh & nearby coast, Con Dao
            ['name' => 'Vung Tau', 'region' => 'southeast', 'description' => 'Weekend beaches and lighthouse views near Ho Chi Minh City.'],
            ['name' => 'Ho Chi Minh City', 'region' => 'southeast', 'description' => 'Dynamic city vibe, history tours and Mekong delta gateways.'],
            ['name' => 'Con Dao', 'region' => 'southeast', 'description' => 'Secluded archipelago, history, beaches and diving.'],

            // Mekong Delta (include Phu Quoc — Kien Giang)
            ['name' => 'Can Tho', 'region' => 'mekong', 'description' => 'Big Mekong city, floating markets and easy delta routes.'],
            ['name' => 'Ben Tre', 'region' => 'mekong', 'description' => 'Coconut country, sampan canals and fruit orchards.'],
            ['name' => 'Tien Giang', 'region' => 'mekong', 'description' => 'Mekong orchards, crafts villages and short trips from the city.'],
            ['name' => 'An Giang', 'region' => 'mekong', 'description' => 'Sam mountain, Chau Doc and the upper Mekong flats.'],
            ['name' => 'Tra Vinh', 'region' => 'mekong', 'description' => 'Khmer-influenced towns, pagodas and slow-paced delta roads.'],
            ['name' => 'Soc Trang', 'region' => 'mekong', 'description' => 'Khmer pagodas, floating life and the lower Mekong flatlands.'],
            ['name' => 'Phu Quoc', 'region' => 'mekong', 'description' => 'Island breaks, night markets, seafood and family-friendly resorts.'],
        ];

        foreach ($items as $item) {
            Destination::query()->updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'region' => $item['region'],
                ],
            );
        }
    }
}
