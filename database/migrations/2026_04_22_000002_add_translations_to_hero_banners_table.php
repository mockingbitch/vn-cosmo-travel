<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_banners', function (Blueprint $table) {
            $table->json('title_translations')->nullable()->after('title');
            $table->json('subtitle_translations')->nullable()->after('subtitle');
            $table->json('cta_text_translations')->nullable()->after('cta_text');
        });

        // Backfill existing values into EN translations.
        DB::table('hero_banners')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $title = is_string($row->title ?? null) ? $row->title : null;
                $subtitle = is_string($row->subtitle ?? null) ? $row->subtitle : null;
                $ctaText = is_string($row->cta_text ?? null) ? $row->cta_text : null;

                DB::table('hero_banners')
                    ->where('id', $row->id)
                    ->update([
                        'title_translations' => $title ? json_encode(['en' => $title], JSON_UNESCAPED_UNICODE) : null,
                        'subtitle_translations' => $subtitle ? json_encode(['en' => $subtitle], JSON_UNESCAPED_UNICODE) : null,
                        'cta_text_translations' => $ctaText ? json_encode(['en' => $ctaText], JSON_UNESCAPED_UNICODE) : null,
                    ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('hero_banners', function (Blueprint $table) {
            $table->dropColumn(['title_translations', 'subtitle_translations', 'cta_text_translations']);
        });
    }
};

