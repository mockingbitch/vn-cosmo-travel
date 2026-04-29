<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $tourIds = Tour::query()->pluck('id')->all();

        if ($tourIds === []) {
            $this->command?->warn('BookingSeeder skipped: no tours in database. Run TourSeeder first.');

            return;
        }

        $statuses = ['pending', 'confirmed', 'cancelled'];
        $now = now();

        $rows = [];
        for ($i = 0; $i < 1000; $i++) {
            $travelDate = fake()->dateTimeBetween('-60 days', '+180 days');

            $rows[] = [
                'tour_id' => fake()->randomElement($tourIds),
                'created_by' => null,
                'updated_by' => null,
                'name' => fake()->name(),
                'email' => sprintf('guest.%d.%s@seed.example', $i, fake()->lexify('????')),
                'phone' => '+84 '.fake()->numerify('9## ### ###'),
                'travel_date' => $travelDate->format('Y-m-d'),
                'people_count' => fake()->numberBetween(1, 8),
                'note' => fake()->boolean(35) ? fake()->sentence() : null,
                'status' => fake()->randomElement($statuses),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 250) as $chunk) {
            Booking::query()->insert($chunk);
        }

        $this->command?->info('Seeded 1000 bookings.');
    }
}
