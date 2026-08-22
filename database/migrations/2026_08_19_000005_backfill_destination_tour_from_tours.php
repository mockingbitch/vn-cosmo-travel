<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('tours')
            ->orderBy('id')
            ->chunkById(200, function ($tours) use ($now): void {
                foreach ($tours as $tour) {
                    if ($tour->destination_id === null) {
                        continue;
                    }

                    $exists = DB::table('destination_tour')
                        ->where('tour_id', $tour->id)
                        ->where('destination_id', $tour->destination_id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    DB::table('destination_tour')->insert([
                        'tour_id' => $tour->id,
                        'destination_id' => $tour->destination_id,
                        'sort_order' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        DB::table('destination_tour')->delete();
    }
};
