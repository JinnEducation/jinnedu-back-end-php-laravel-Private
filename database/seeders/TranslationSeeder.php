<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Translation::query()->updateOrCreate(['id' => 1], ['langid' => 1, 'labelid' => 1, 'title'=>'English']);
        Translation::query()->updateOrCreate(['id' => 2], ['langid' => 2, 'labelid' => 1, 'title'=>'الانجليزية']);
        
        Translation::query()->updateOrCreate(['id' => 3], ['langid' => 1, 'labelid' => 2, 'title'=>'Arabic']);
        Translation::query()->updateOrCreate(['id' => 4], ['langid' => 2, 'labelid' => 2, 'title'=>'العربية']);
        
        Translation::query()->updateOrCreate(['id' => 5], ['langid' => 1, 'labelid' => 3, 'title'=>'Home']);
        Translation::query()->updateOrCreate(['id' => 6], ['langid' => 2, 'labelid' => 3, 'title'=>'الرئيسية']);
        
        Translation::query()->updateOrCreate(['id' => 7], ['langid' => 1, 'labelid' => 4, 'title'=>'Languages']);
        Translation::query()->updateOrCreate(['id' => 8], ['langid' => 2, 'labelid' => 4, 'title'=>'اللغات']);
        
        Translation::query()->updateOrCreate(['id' => 9], ['langid' => 1, 'labelid' => 5, 'title'=>'Dashboard!']);
        Translation::query()->updateOrCreate(['id' => 10], ['langid' => 2, 'labelid' => 5, 'title'=>'لوحة التحكم!']);
        
        Translation::query()->updateOrCreate(['id' => 11], ['langid' => 1, 'labelid' => 6, 'title'=>'Default']);
        Translation::query()->updateOrCreate(['id' => 12], ['langid' => 2, 'labelid' => 6, 'title'=>'الافرتاضية']);
        
        Translation::query()->updateOrCreate(['id' => 13], ['langid' => 1, 'labelid' => 7, 'title'=>'Pos System']);
        Translation::query()->updateOrCreate(['id' => 14], ['langid' => 2, 'labelid' => 7, 'title'=>'نقطة بيع']);
        
        Translation::query()->updateOrCreate(['id' => 15], ['langid' => 1, 'labelid' => 8, 'title'=>'Account']);
        Translation::query()->updateOrCreate(['id' => 16], ['langid' => 2, 'labelid' => 8, 'title'=>'المحاسبة']);
    }
}
