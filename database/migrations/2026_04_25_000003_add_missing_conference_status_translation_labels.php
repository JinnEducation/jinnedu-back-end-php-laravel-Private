<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $labels = [
        'conference-not-started-yet' => ['Conference has not started yet', 'لم تبدأ الحصة بعد', 'La conférence n a pas encore commencé', 'Die Konferenz hat noch nicht begonnen'],
        'waiting-for-tutor-to-start-conference' => ['Waiting for tutor to start the class', 'بانتظار المعلم لبدء الحصة', 'En attente du tuteur pour démarrer le cours', 'Warten auf den Tutor, um den Unterricht zu starten'],
        'conference-has-ended' => ['Conference has ended', 'انتهت الحصة', 'La conférence est terminée', 'Die Konferenz ist beendet'],
    ];

    public function up()
    {
        if (! Schema::hasTable('labels') || ! Schema::hasTable('translations') || ! Schema::hasTable('languages')) {
            return;
        }

        $languages = DB::table('languages')->whereIn('shortname', ['en', 'ar', 'fr', 'de'])->get()->keyBy('shortname');

        foreach ($this->labels as $name => $values) {
            $label = DB::table('labels')->where('file', 'global')->where('name', $name)->first();
            $labelId = $label?->id ?? DB::table('labels')->insertGetId([
                'name' => $name,
                'file' => 'global',
                'title' => $values[0],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($label) {
                DB::table('labels')->where('id', $labelId)->update([
                    'title' => $values[0],
                    'updated_at' => now(),
                ]);
            }

            $translations = ['en' => $values[0], 'ar' => $values[1], 'fr' => $values[2], 'de' => $values[3]];
            foreach ($translations as $shortname => $title) {
                if (! isset($languages[$shortname])) {
                    continue;
                }

                DB::table('translations')->updateOrInsert(
                    ['labelid' => $labelId, 'langid' => $languages[$shortname]->id],
                    ['title' => $title, 'updated_at' => now(), 'created_at' => now()]
                );
            }
        }
    }

    public function down()
    {
        if (! Schema::hasTable('labels') || ! Schema::hasTable('translations')) {
            return;
        }

        $labelIds = DB::table('labels')->where('file', 'global')->whereIn('name', array_keys($this->labels))->pluck('id');
        DB::table('translations')->whereIn('labelid', $labelIds)->delete();
        DB::table('labels')->whereIn('id', $labelIds)->delete();
    }
};
