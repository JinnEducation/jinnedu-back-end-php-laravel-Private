<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('help_articles')) {
            Schema::table('help_articles', function (Blueprint $table) {
                if (! Schema::hasColumn('help_articles', 'slug')) {
                    $table->string('slug')->nullable()->after('audience');
                }
                if (! Schema::hasColumn('help_articles', 'icon')) {
                    $table->string('icon')->nullable()->after('slug');
                }
                if (Schema::hasColumn('help_articles', 'image')) {
                    $table->dropColumn('image');
                }
                if (Schema::hasColumn('help_articles', 'date')) {
                    $table->dropColumn('date');
                }
                if (Schema::hasColumn('help_articles', 'sort_order')) {
                    $table->dropColumn('sort_order');
                }
            });

        }

        if (Schema::hasTable('help_article_langs')) {
            Schema::table('help_article_langs', function (Blueprint $table) {
                if (Schema::hasColumn('help_article_langs', 'slug')) {
                    $table->dropUnique('help_article_langs_slug_unique');
                    $table->dropColumn('slug');
                }
                if (Schema::hasColumn('help_article_langs', 'short_description')) {
                    $table->dropColumn('short_description');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('help_articles')) {
            Schema::table('help_articles', function (Blueprint $table) {
                if (Schema::hasColumn('help_articles', 'slug')) {
                    $table->dropColumn('slug');
                }
                if (Schema::hasColumn('help_articles', 'icon')) {
                    $table->dropColumn('icon');
                }
                if (! Schema::hasColumn('help_articles', 'image')) {
                    $table->string('image')->nullable();
                }
                if (! Schema::hasColumn('help_articles', 'date')) {
                    $table->date('date')->nullable();
                }
                if (! Schema::hasColumn('help_articles', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0);
                }
            });
        }

        if (Schema::hasTable('help_article_langs')) {
            Schema::table('help_article_langs', function (Blueprint $table) {
                if (! Schema::hasColumn('help_article_langs', 'slug')) {
                    $table->string('slug')->nullable();
                    $table->unique('slug');
                }
                if (! Schema::hasColumn('help_article_langs', 'short_description')) {
                    $table->text('short_description')->nullable();
                }
            });
        }
    }
};
