<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Starter catalog. Admins may rename, re-categorize, reorder or add more.
     *
     * @var list<array{name: string, category: string}>
     */
    private const DEFAULT_TYPES = [
        ['name' => 'Price for 1 person', 'category' => 'Group size'],
        ['name' => 'Price for 2 people', 'category' => 'Group size'],
        ['name' => 'Price including car', 'category' => 'Transport'],
        ['name' => 'Price excluding car', 'category' => 'Transport'],
    ];

    public function up(): void
    {
        $now = now();

        foreach (self::DEFAULT_TYPES as $i => $type) {
            $exists = DB::table('tour_price_types')->where('name', $type['name'])->exists();
            if ($exists) {
                continue;
            }

            DB::table('tour_price_types')->insert([
                'name' => $type['name'],
                'category' => $type['category'],
                'sort_order' => $i + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $fallbackTypeId = DB::table('tour_price_types')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('id');

        if ($fallbackTypeId === null) {
            return;
        }

        // Existing tours keep their single price, now expressed as their default price row.
        DB::table('tours')
            ->orderBy('id')
            ->chunkById(100, function ($tours) use ($fallbackTypeId, $now): void {
                foreach ($tours as $tour) {
                    $hasRows = DB::table('tour_prices')->where('tour_id', $tour->id)->exists();
                    if ($hasRows) {
                        continue;
                    }

                    DB::table('tour_prices')->insert([
                        'tour_id' => $tour->id,
                        'tour_price_type_id' => $fallbackTypeId,
                        'amount' => max(0, (int) $tour->price),
                        'currency' => filled($tour->currency ?? null) ? $tour->currency : 'USD',
                        'note' => null,
                        'sort_order' => 0,
                        'is_default' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        DB::table('tour_prices')->delete();

        DB::table('tour_price_types')
            ->whereIn('name', array_column(self::DEFAULT_TYPES, 'name'))
            ->delete();
    }
};
