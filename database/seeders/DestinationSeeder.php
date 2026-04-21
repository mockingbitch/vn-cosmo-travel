<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Hanoi',
                'description' => 'Old Quarter, street food, culture and day trips to nearby icons.',
            ],
            [
                'name' => 'Ha Long Bay',
                'description' => 'Cruises, limestone karsts and one of Vietnam’s most iconic landscapes.',
            ],
            [
                'name' => 'Da Nang',
                'description' => 'Coastal city with easy access to Hoi An and the mountains.',
            ],
            [
                'name' => 'Ho Chi Minh City',
                'description' => 'Dynamic city vibe, history tours and Mekong delta gateways.',
            ],
        ];

        foreach ($items as $item) {
            Destination::query()->updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                ],
            );
        }
    }
}
