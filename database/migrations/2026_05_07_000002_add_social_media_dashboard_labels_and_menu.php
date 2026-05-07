<?php

use App\Models\SocialMediaLink;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $labels = [
        'social-media-links' => ['Social media links', 'روابط السوشيال ميديا'],
        'social-media-settings' => ['Social media settings', 'إعدادات السوشيال ميديا'],
        'manage-footer-social-media-links' => ['Manage footer social media links', 'إدارة روابط السوشيال ميديا في الفوتر'],
        'platform' => ['Platform', 'المنصة'],
        'social-link' => ['Social link', 'رابط السوشيال'],
        'enter-social-link' => ['Enter social link', 'أدخل رابط السوشيال'],
        'social-media-updated-successfully' => ['Social media links updated successfully', 'تم تحديث روابط السوشيال ميديا بنجاح'],
    ];

    public function up(): void
    {
        $this->addLabels();
        $this->addMenuItem();
    }

    public function down(): void
    {
        if (Schema::hasTable('labels') && Schema::hasTable('translations')) {
            $labelIds = DB::table('labels')
                ->where('file', 'global')
                ->whereIn('name', array_keys($this->labels))
                ->pluck('id');

            DB::table('translations')->whereIn('labelid', $labelIds)->delete();
            DB::table('labels')->whereIn('id', $labelIds)->delete();
        }

        if (Schema::hasTable('menus')) {
            DB::table('menus')->where('route', 'settings.social-media')->delete();
        }
    }

    private function addLabels(): void
    {
        if (! Schema::hasTable('labels') || ! Schema::hasTable('translations') || ! Schema::hasTable('languages')) {
            return;
        }

        $languages = DB::table('languages')->whereIn('shortname', ['en', 'ar', 'fr', 'de'])->get()->keyBy('shortname');

        foreach ($this->labels as $name => [$english, $arabic]) {
            $label = DB::table('labels')->where('file', 'global')->where('name', $name)->first();
            $labelId = $label?->id ?: DB::table('labels')->insertGetId([
                'name' => $name,
                'file' => 'global',
                'title' => $english,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($label) {
                DB::table('labels')->where('id', $labelId)->update([
                    'title' => $english,
                    'updated_at' => now(),
                ]);
            }

            foreach (['en' => $english, 'ar' => $arabic, 'fr' => $english, 'de' => $english] as $shortname => $title) {
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

    private function addMenuItem(): void
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        $parent = DB::table('menus')->where('title', 'settings-management')->where('p_id', 0)->first();

        if (! $parent || DB::table('menus')->where('route', 'settings.social-media')->exists()) {
            return;
        }

        DB::table('menus')->insert([
            'p_id' => $parent->id,
            'title' => 'social-media-links',
            'name' => 'social-media-links',
            'route' => 'settings.social-media',
            'type' => SocialMediaLink::class,
            'active_routes' => 'settings.social-media',
            'status' => 0,
            'invisible' => 0,
            'sortable' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
