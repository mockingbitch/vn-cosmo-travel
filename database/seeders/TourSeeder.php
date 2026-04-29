<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourImage;
use App\Models\TourItinerary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hanoi = Destination::where('slug', 'hanoi')->first();
        $halong = Destination::where('slug', 'ha-long-bay')->first();
        $danang = Destination::where('slug', 'da-nang')->first();
        $hcm = Destination::where('slug', 'ho-chi-minh-city')->first();

        $items = [
            [
                'destination_id' => $hanoi?->id,
                'title' => 'Hanoi Food & Culture Essentials',
                'duration' => 3,
                'price' => 3500000,
                'thumbnail' => 'https://images.unsplash.com/photo-1555921015-5532091f6026?auto=format&fit=crop&w=1400&q=80',
                'description' => 'A fast, comfortable introduction to Hanoi: Old Quarter walks, street food, and cultural landmarks.',
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrive & Old Quarter', 'description' => 'Settle in, evening street food experience.'],
                    ['day' => 2, 'title' => 'Culture Day', 'description' => 'Key landmarks, museums and local lunch.'],
                    ['day' => 3, 'title' => 'Day trip', 'description' => 'Flexible day trip based on your preferences.'],
                ],
                'gallery' => [
                    'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1526481280695-3c687fd643ed?auto=format&fit=crop&w=1400&q=80',
                ],
            ],
            [
                'destination_id' => $halong?->id,
                'title' => 'Ha Long Bay Overnight Cruise',
                'duration' => 2,
                'price' => 5200000,
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1400&q=80',
                'description' => 'A high-conversion classic: comfortable cruise, stunning scenery, easy logistics.',
                'itinerary' => [
                    ['day' => 1, 'title' => 'Cruise & Kayak', 'description' => 'Board cruise, lunch, kayaking, sunset.'],
                    ['day' => 2, 'title' => 'Morning views', 'description' => 'Breakfast, light activities, return.'],
                ],
                'gallery' => [
                    'https://images.unsplash.com/photo-1516862523118-a3724eb136d7?auto=format&fit=crop&w=1400&q=80',
                ],
            ],
            [
                'destination_id' => $danang?->id,
                'title' => 'Da Nang & Hoi An Highlights',
                'duration' => 4,
                'price' => 7400000,
                'thumbnail' => 'https://images.unsplash.com/photo-1500622944204-b135684e99fd?auto=format&fit=crop&w=1400&q=80',
                'description' => 'Coastal relaxation + heritage charm with comfortable pacing and great photos.',
                'itinerary' => [
                    ['day' => 1, 'title' => 'Beach & city', 'description' => 'Arrive and explore the coast.'],
                    ['day' => 2, 'title' => 'Hoi An old town', 'description' => 'Lantern streets, food, crafts.'],
                    ['day' => 3, 'title' => 'Nature day', 'description' => 'Mountains or countryside based on your style.'],
                    ['day' => 4, 'title' => 'Departure', 'description' => 'Flexible checkout and transfer.'],
                ],
                'gallery' => [
                    'https://images.unsplash.com/photo-1559599238-9d7a4aa4c2bf?auto=format&fit=crop&w=1400&q=80',
                ],
            ],
            [
                'destination_id' => $hcm?->id,
                'title' => 'Ho Chi Minh City + Mekong Day Trip',
                'duration' => 3,
                'price' => 6100000,
                'thumbnail' => 'https://images.unsplash.com/photo-1533371452382-d45a9da51ad9?auto=format&fit=crop&w=1400&q=80',
                'description' => 'History + modern city energy, plus an easy Mekong experience.',
                'itinerary' => [
                    ['day' => 1, 'title' => 'City essentials', 'description' => 'Landmarks and local coffee stops.'],
                    ['day' => 2, 'title' => 'Mekong day trip', 'description' => 'Boat rides and countryside lunch.'],
                    ['day' => 3, 'title' => 'Free time', 'description' => 'Markets and departure.'],
                ],
                'gallery' => [
                    'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=1400&q=80',
                ],
            ],
        ];

        foreach ($items as $item) {
            if (! $item['destination_id']) {
                continue;
            }

            $tour = Tour::query()->updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'destination_id' => $item['destination_id'],
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'duration' => $item['duration'],
                    'price' => $item['price'],
                    'thumbnail' => $item['thumbnail'],
                    'status' => Tour::STATUS_ACTIVE,
                ],
            );

            TourItinerary::query()->where('tour_id', $tour->id)->delete();
            foreach ($item['itinerary'] as $it) {
                TourItinerary::create([
                    'tour_id' => $tour->id,
                    'day' => $it['day'],
                    'title' => $it['title'],
                    'description' => $it['description'],
                ]);
            }

            TourImage::query()->where('tour_id', $tour->id)->delete();
            foreach (array_values($item['gallery']) as $idx => $path) {
                TourImage::create([
                    'tour_id' => $tour->id,
                    'path' => $path,
                    'sort_order' => $idx,
                ]);
            }
        }
    }
}
