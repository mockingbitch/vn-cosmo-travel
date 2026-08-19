<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-managed catalog of tour price types (label + optional category),
     * so price naming is data instead of hard-coded values.
     */
    public function up(): void
    {
        Schema::create('tour_price_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('category', 80)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_price_types');
    }
};
