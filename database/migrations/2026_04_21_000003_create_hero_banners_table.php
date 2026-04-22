<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_link')->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->json('title_translations')->nullable();
            $table->json('subtitle_translations')->nullable();
            $table->json('cta_text_translations')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->index('is_current');
            $table->index('archived_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_banners');
    }
};
