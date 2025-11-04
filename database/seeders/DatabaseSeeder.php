<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     RoleSeeder::class,
        //     UserSeeder::class,
        //     MenuSeeder::class,
        //     LanguageSeeder::class,
        //     LabelSeeder::class,
        //     TranslationSeeder::class,
            
        //     LevelSeeder::class,
        //     CountrySeeder::class,
        //     ExperienceSeeder::class,
        //     WeekDaySeeder::class,
        //     BlogSeeder::class,
        // ]);
        $this->call([
            MenuNewSeeder::class,
        ]);
    }
}
