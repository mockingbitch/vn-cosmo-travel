<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tour_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16); // service | amenity
            $table->string('label', 120);
            $table->timestamps();

            $table->unique(['type', 'label']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_attributes');
    }
};
