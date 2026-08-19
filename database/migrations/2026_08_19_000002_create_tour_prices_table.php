<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per (tour, price type): a tour can carry several prices.
     */
    public function up(): void
    {
        Schema::create('tour_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_price_type_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('currency', 3)->default('USD');
            $table->string('note', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['tour_id', 'tour_price_type_id']);
            $table->index(['tour_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_prices');
    }
};
