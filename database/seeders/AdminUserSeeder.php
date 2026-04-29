<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = app()->isProduction()
            ? 'admin@vietnamcosmotravel.com'
            : 'admin@vncosmo.test';

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123456'),
                'is_admin' => true,
                'can_access_panel' => true,
                'status' => User::STATUS_ACTIVE,
            ],
        );
    }
}
