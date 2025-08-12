<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
        Experience::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('FORMAL_TEACHING_EXP')]);
        Experience::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('INFORMAL_SETTING')]);
        Experience::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('NONE')]);
    }
}
