<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add slug column if it doesn't exist
        if (!Schema::hasColumn('group_classes', 'slug')) {
            Schema::table('group_classes', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        // 2) Copy EN slug from group_class_langs to group_classes.slug (only when empty)
        DB::statement("
            UPDATE group_classes gc
            JOIN group_class_langs gcl ON CAST(gcl.classid AS UNSIGNED) = gc.id
            JOIN languages l ON l.id = gcl.language_id
            SET gc.slug = gcl.slug
            WHERE l.shortname = 'en'
              AND (gc.slug IS NULL OR TRIM(gc.slug) = '')
              AND gcl.slug IS NOT NULL
              AND TRIM(gcl.slug) <> ''
        ");

        // 3) Any remaining empty slugs => give them a guaranteed unique slug (no NULL)
        DB::statement("
            UPDATE group_classes
            SET slug = CONCAT('group-class-', id)
            WHERE slug IS NULL OR TRIM(slug) = ''
        ");

        // 4) Fix duplicates (if same slug appears more than once) by appending -id
        DB::statement("
            UPDATE group_classes gc
            JOIN (
                SELECT slug
                FROM group_classes
                GROUP BY slug
                HAVING COUNT(*) > 1
            ) d ON d.slug = gc.slug
            SET gc.slug = CONCAT(gc.slug, '-', gc.id)
        ");

        // 5) Add unique index if it doesn't exist
        $indexes = DB::select("
            SHOW INDEX
            FROM group_classes
            WHERE Key_name = 'group_classes_slug_unique'
        ");

        if (count($indexes) === 0) {
            Schema::table('group_classes', function (Blueprint $table) {
                $table->unique('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('group_classes', 'slug')) {
            Schema::table('group_classes', function (Blueprint $table) {
                try { $table->dropUnique('group_classes_slug_unique'); } catch (\Throwable $e) {}
                $table->dropColumn('slug');
            });
        }
    }
};
