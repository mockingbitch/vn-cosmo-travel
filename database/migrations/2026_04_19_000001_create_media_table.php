<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path')->unique();
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size');
            $table->string('alt_text')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['mime_type']);
            $table->index(['file_name']);
        });
    }

    public function down(): void
    {
        // Be defensive: depending tables might still exist if rollback order differs.
        Schema::dropIfExists('media_usages');
        Schema::dropIfExists('media');
    }
};

