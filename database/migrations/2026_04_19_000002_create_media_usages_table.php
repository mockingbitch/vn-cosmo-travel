<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->morphs('model');
            $table->string('field')->nullable(); // e.g. thumbnail, hero_image, gallery
            $table->timestamps();

            $table->unique(['media_id', 'model_type', 'model_id', 'field']);
            // $table->morphs('model') already creates an index for (model_type, model_id).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_usages');
    }
};

