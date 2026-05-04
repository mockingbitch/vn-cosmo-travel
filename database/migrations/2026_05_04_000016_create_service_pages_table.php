<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CMS pages for “Other services”: airport taxi, visa, bus/flight/train ticket, SIM card.
 * Rows are keyed by `type` (see App\Models\ServicePage::allowedTypes()); seeded via ServicePagesSeeder.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->unique();
            $table->string('status', 16)->default('active')->index();
            $table->json('translations')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_pages');
    }
};
