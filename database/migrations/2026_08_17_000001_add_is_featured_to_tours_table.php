<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('status');
            $table->unsignedSmallInteger('featured_sort')->nullable()->after('is_featured');

            $table->index(['is_featured', 'featured_sort']);
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropIndex(['is_featured', 'featured_sort']);
            $table->dropColumn(['is_featured', 'featured_sort']);
        });
    }
};
