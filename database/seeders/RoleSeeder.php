<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;

use App\Models\Menu;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Bouncer;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Bouncer::role()->firstOrCreate([
            'name' => 'superadmin',
            'title' => 'super-administrator',
        ]);
        \Bouncer::allow('superadmin')->everything();

        //=========================================================  
          
        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'admin',
            'title' => 'administrator',
        ]);
        \Bouncer::allow('admin')->everything();
        
        //=========================================================

    }
}
