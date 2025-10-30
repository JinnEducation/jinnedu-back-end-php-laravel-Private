<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 🔹 SUPERADMIN
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@jinnedu.com'],
            [
                'name' => 'Super Admin',
                'type' => 0, // أو student لو حابب
                'password' => Hash::make('12345678'),
            ]
        );

        if (method_exists($superadmin, 'assign')) {
            $superadmin->assign('superadmin');
        }

        // 🔹 ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@jinnedu.com'],
            [
                'name' => 'Admin',
                'type' => 0,
                'password' => Hash::make('12345678'),
            ]
        );

        if (method_exists($admin, 'assign')) {
            $admin->assign('admin');
        }
    }
}
