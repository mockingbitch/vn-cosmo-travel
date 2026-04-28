<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('destinations', 'name_en')) {
            return;
        }

        Schema::table('destinations', function (Blueprint $table): void {
            $table->string('name_en', 255)->nullable();
            $table->string('name_vi', 255)->nullable();
        });

        if (Schema::hasColumn('destinations', 'name')) {
            $rows = DB::table('destinations')->get(['id', 'name']);
            foreach ($rows as $row) {
                $n = (string) ($row->name ?? '');
                DB::table('destinations')->where('id', $row->id)->update([
                    'name_en' => $n,
                    'name_vi' => $n,
                ]);
            }
            Schema::table('destinations', function (Blueprint $table): void {
                $table->dropColumn('name');
            });
        }

        DB::table('destinations')->whereNull('name_en')->update(['name_en' => '']);
        DB::table('destinations')->whereNull('name_vi')->update(['name_vi' => '']);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('destinations', 'name_en')) {
            return;
        }

        Schema::table('destinations', function (Blueprint $table): void {
            $table->string('name', 255)->nullable();
        });

        $rows = DB::table('destinations')->get(['id', 'name_en']);
        foreach ($rows as $row) {
            DB::table('destinations')->where('id', $row->id)->update([
                'name' => (string) ($row->name_en ?? ''),
            ]);
        }

        Schema::table('destinations', function (Blueprint $table): void {
            $table->dropColumn(['name_en', 'name_vi']);
        });
    }
};
