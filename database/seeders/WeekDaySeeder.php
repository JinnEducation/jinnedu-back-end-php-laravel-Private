<?php

namespace Database\Seeders;

use App\Models\WeekDay;
use Illuminate\Database\Seeder;

class WeekDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
        WeekDay::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('Saturday')]);
        WeekDay::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('Sunday')]);
        WeekDay::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('Monday	')]);
        WeekDay::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('Tuesday')]);
        WeekDay::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('Wednesday')]);
        WeekDay::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('Thursday	')]);
        WeekDay::query()->updateOrCreate(['id' => $i++], ['name' => strtolower('Friday	')]);
    }
}
