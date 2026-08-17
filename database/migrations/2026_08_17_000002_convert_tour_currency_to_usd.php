<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const VND_TO_USD_RATE = 25000;

    public function up(): void
    {
        DB::table('tours')
            ->where('currency', 'VND')
            ->orderBy('id')
            ->chunkById(100, function ($tours): void {
                foreach ($tours as $tour) {
                    DB::table('tours')
                        ->where('id', $tour->id)
                        ->update([
                            'currency' => 'USD',
                            'price' => max(1, (int) round(((int) $tour->price) / self::VND_TO_USD_RATE)),
                        ]);
                }
            });

        DB::table('tours')
            ->where(function ($query): void {
                $query->whereNull('currency')
                    ->orWhere('currency', '');
            })
            ->update(['currency' => 'USD']);
    }

    public function down(): void
    {
        // Irreversible: VND amounts cannot be restored accurately after conversion.
    }
};
