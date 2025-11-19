<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteTranslationsSeeder extends Seeder
{
    public function run()
    {
        $en = include base_path('lang/en/site1.php');
        $ar = include base_path('lang/ar/site1.php');

        $keys = array_unique(array_merge(array_keys($en), array_keys($ar)));
        sort($keys, SORT_STRING | SORT_FLAG_CASE);

        foreach ($keys as $key) {
            $name = mb_strtolower($key, 'UTF-8');
            $enTitle = $en[$key] ?? '';
            $arTitle = $ar[$key] ?? '';

            // labels
            $labelId = DB::table('labels')->updateOrInsert(
                ['name' => $name],
                ['file' => 'site', 'title' => $enTitle, 'created_at' => now()]
            );

            // لو استخدمت updateOrInsert يرجع bool، لذا نجيب id صريحاً:
            $labelId = DB::table('labels')->where('name', $name)->value('id');

            // translations EN
            DB::table('translations')->updateOrInsert(
                ['langid' => 1, 'labelid' => $labelId],
                ['title' => $enTitle, 'created_at' => now()]
            );

            // translations AR
            DB::table('translations')->updateOrInsert(
                ['langid' => 2, 'labelid' => $labelId],
                ['title' => $arTitle, 'created_at' => now()]
            );
        }
    }
}