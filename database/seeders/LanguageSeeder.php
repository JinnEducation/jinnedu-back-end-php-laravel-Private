<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
        Language::query()->updateOrCreate(['id' => $i++], ['name' => 'english', 'direction' => 'ltr', 'shortname'=>'en', 'icon'=>'united-states.svg', 'dirword'=>'left', 'main'=>1, 'status'=>1]);
        Language::query()->updateOrCreate(['id' => $i++], ['name' => 'arabic', 'direction' => 'rtl', 'shortname'=>'ar', 'icon'=>'saudi-arabia.svg', 'dirword'=>'right', 'main'=>0, 'status'=>1]);
        
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'sq', 'name' => strtolower('Albanian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'hy', 'name' => strtolower('Armenian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'az', 'name' => strtolower('Azerbaijani'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'eu', 'name' => strtolower('Basque'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'bg', 'name' => strtolower('Bulgarian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ca', 'name' => strtolower('Catalan'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'zh', 'name' => strtolower('Chinese (Mandarin)'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ceb', 'name' => strtolower('Cebuano'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'crh', 'name' => strtolower('Crimean Tatar'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'hr', 'name' => strtolower('Croatian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'cs', 'name' => strtolower('Czech'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'da', 'name' => strtolower('Danish'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'de', 'name' => strtolower('German'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'el', 'name' => strtolower('Greek'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'et', 'name' => strtolower('Estonian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'fa', 'name' => strtolower('Farsi'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'fi', 'name' => strtolower('Finnish'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'fr', 'name' => strtolower('French'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'gl', 'name' => strtolower('Galician'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ka', 'name' => strtolower('Georgian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'hi', 'name' => strtolower('Hindi'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'he', 'name' => strtolower('Hebrew'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'id', 'name' => strtolower('Indonesian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'it', 'name' => strtolower('Italian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ja', 'name' => strtolower('Japanese'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'kk', 'name' => strtolower('Kazakh'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ko', 'name' => strtolower('Korean'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'la', 'name' => strtolower('Latin'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'lt', 'name' => strtolower('Lithuanian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'hu', 'name' => strtolower('Hungarian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ms', 'name' => strtolower('Malay'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ml', 'name' => strtolower('Malayalam'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'min', 'name' => strtolower('Minangkabau'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'nl', 'name' => strtolower('Dutch'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'no', 'name' => strtolower('Norwegian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'nn', 'name' => strtolower('Nynorsk'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'pl', 'name' => strtolower('Polish'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'pt', 'name' => strtolower('Portuguese'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ro', 'name' => strtolower('Romanian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ru', 'name' => strtolower('Russian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'sr', 'name' => strtolower('Serbian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'sh', 'name' => strtolower('Serbo-Croatian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'sk', 'name' => strtolower('Slovak'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'sl', 'name' => strtolower('Slovenian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'es', 'name' => strtolower('Spanish'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'sv', 'name' => strtolower('Swedish'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'tr', 'name' => strtolower('Turkish'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ua', 'name' => strtolower('Ukrainian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ur', 'name' => strtolower('Urdu'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'uz', 'name' => strtolower('Uzbek'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'vi', 'name' => strtolower('Vietnamese'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'vo', 'name' => strtolower('Volapük'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'war', 'name' => strtolower('Waray'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'sah', 'name' => strtolower('Yakut'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'mk', 'name' => strtolower('Macedonian'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'bn', 'name' => strtolower('Bengali'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'yue', 'name' => strtolower('Chinese (Cantonese)'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'gu', 'name' => strtolower('Gujarati'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'is', 'name' => strtolower('Icelandic'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ga', 'name' => strtolower('Irish'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'jv', 'name' => strtolower('Javanese'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'kn', 'name' => strtolower('Kannada'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'mr', 'name' => strtolower('Marathi'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ps', 'name' => strtolower('Pashto'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'pa', 'name' => strtolower('Punjabi'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'su', 'name' => strtolower('Sundanese'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'tl', 'name' => strtolower('Tagalog'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'ta', 'name' => strtolower('Tamil'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'te', 'name' => strtolower('Telugu'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
        Language::query()->updateOrCreate(['id' => $i++], ['shortname'=>'th', 'name' => strtolower('Thai'), 'direction' => 'ltr', 'icon'=>'', 'dirword'=>'left', 'main'=>0, 'status'=>0]);
    }
}
