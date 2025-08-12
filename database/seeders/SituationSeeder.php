<?php

namespace Database\Seeders;

use App\Models\Situation;
use Illuminate\Database\Seeder;

class SituationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
        Situation::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('ANOTHER_TEACHING_JOB')]);
        Situation::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('ANOTHER_NON_TEACHING_JOB')]);
        Situation::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('STUDENT')]);
        Situation::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('OTHER_COMMITMENTS')]);
        Situation::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('NONE')]);
    }
}
