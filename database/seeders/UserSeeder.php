<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $superadminCheck = User::where('email', '=', 'superadmin@jinnedu.com')->first();
        if ($superadminCheck === null) {
            $superadmin = User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'superadmin@jinnedu.com',
                'password' => bcrypt('12345678')
            ]);
            $superadmin->assign('superadmin');
        }
        //=======================================
        $adminCheck = User::where('email', '=', 'admin@jinnedu.com')->first();
        if ($adminCheck === null) {
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@jinnedu.com',
                'password' => bcrypt('12345678')
            ]);
            $admin->assign('admin');
        }
    }
}
