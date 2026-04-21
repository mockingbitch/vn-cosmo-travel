<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_banners', function (Blueprint $table) {
            $table->boolean('is_current')->default(false)->after('is_active');
            $table->timestamp('archived_at')->nullable()->after('is_current');
            $table->index(['is_current']);
            $table->index(['archived_at']);
        });

        $currentId = DB::table('hero_banners')->max('id');
        if ($currentId) {
            DB::table('hero_banners')->update(['is_current' => false]);
            DB::table('hero_banners')->where('id', $currentId)->update(['is_current' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('hero_banners', function (Blueprint $table) {
            $table->dropIndex(['is_current']);
            $table->dropIndex(['archived_at']);
            $table->dropColumn(['is_current', 'archived_at']);
        });
    }
};

