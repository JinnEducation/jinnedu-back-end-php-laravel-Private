<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::query()->updateOrCreate(['id' => 1], ['name' => 'englisgh', 'file' => 'main', 'title'=>'Englisgh']);
        Label::query()->updateOrCreate(['id' => 2], ['name' => 'arabic', 'file' => 'main', 'title'=>'Arabic']);
        Label::query()->updateOrCreate(['id' => 3], ['name' => 'home', 'file' => 'main', 'title'=>'Home']);
        Label::query()->updateOrCreate(['id' => 4], ['name' => 'language', 'file' => 'main', 'title'=>'Englisgh']);
        
        Label::query()->updateOrCreate(['id' => 5], ['name' => 'dashboard', 'file' => 'main', 'title'=>'Dashboard!']);
        Label::query()->updateOrCreate(['id' => 6], ['name' => 'default', 'file' => 'main', 'title'=>'Default']);
        Label::query()->updateOrCreate(['id' => 7], ['name' => 'pos-system', 'file' => 'main', 'title'=>'Pos System']);
        Label::query()->updateOrCreate(['id' => 8], ['name' => 'account', 'file' => 'main', 'title'=>'Account']);
        
    }
}
