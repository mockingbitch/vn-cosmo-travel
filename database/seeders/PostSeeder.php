<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::query()->updateOrCreate(
            ['slug' => 'guides'],
            ['name' => 'Guides', 'slug' => 'guides'],
        );

        $items = [
            [
                'title' => 'Best time to visit Vietnam: weather by region',
                'content' => '<p>Vietnam spans multiple climates. This guide helps you choose the best season depending on your route.</p><h2>North</h2><p>Cooler winters and hot, humid summers.</p><h2>Central</h2><p>Great beaches, but watch for rainy months.</p><h2>South</h2><p>Warm year-round with wet/dry seasons.</p>',
            ],
            [
                'title' => 'Vietnam travel costs: a realistic budget for 3–10 days',
                'content' => '<p>Plan confidently with a simple budget breakdown: transport, activities, food, and accommodation.</p><h2>Quick ranges</h2><ul><li>Comfort: mid-range hotels + curated tours</li><li>Premium: private transfers + upgraded stays</li></ul>',
            ],
            [
                'title' => 'Hanoi vs Ho Chi Minh City: which one should you start with?',
                'content' => '<p>Both cities are amazing. The best choice depends on your route and flight connections.</p><h2>Start in Hanoi</h2><p>Ideal for northern routes and cooler seasons.</p><h2>Start in HCMC</h2><p>Perfect for southern routes and dynamic city energy.</p>',
            ],
        ];

        foreach ($items as $item) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'category_id' => $category->id,
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'thumbnail_media_id' => null,
                ],
            );
        }
    }
}
