<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
        Level::query()->updateOrCreate(['id' => $i++], ['name' => 'elenentary']);
        Level::query()->updateOrCreate(['id' => $i++], ['name' => 'pre']);
        Level::query()->updateOrCreate(['id' => $i++], ['name' => 'inter']);
        Level::query()->updateOrCreate(['id' => $i++], ['name' => 'upper']);
        Level::query()->updateOrCreate(['id' => $i++], ['name' => 'advanced']);
        Level::query()->updateOrCreate(['id' => $i++], ['name' => 'profi']);
        Level::query()->updateOrCreate(['id' => $i++], ['name' => 'native']);
    }
}
