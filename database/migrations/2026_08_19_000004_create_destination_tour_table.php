<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A tour can cover several destinations. `tours.destination_id` stays as the
     * primary one (first row here), so list columns, filters and cards keep
     * reading a single indexed column.
     */
    public function up(): void
    {
        Schema::create('destination_tour', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['tour_id', 'destination_id']);
            $table->index(['destination_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_tour');
    }
};
