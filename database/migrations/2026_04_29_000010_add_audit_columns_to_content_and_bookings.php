<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table): void {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::table('destinations', function (Blueprint $table): void {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });

        Schema::table('destinations', function (Blueprint $table): void {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });

        Schema::table('tours', function (Blueprint $table): void {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
