<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table): void {
            $table->string('status', 16)->default('active')->after('slug');
            $table->index(['status']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->string('status', 16)->default('active')->after('slug');
            $table->index(['status']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->string('status', 16)->default('active')->after('email');
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });

        Schema::table('tours', function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
