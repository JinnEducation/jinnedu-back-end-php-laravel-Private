<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $links = [
        ['key' => 'facebook', 'name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'hover_class' => 'hover:bg-primary', 'sort_order' => 1],
        ['key' => 'instagram', 'name' => 'Instagram', 'icon' => 'fab fa-instagram', 'hover_class' => 'hover:bg-pink-600', 'sort_order' => 2],
        ['key' => 'x', 'name' => 'X', 'icon' => 'fab fa-x-twitter', 'hover_class' => 'hover:bg-gray-800', 'sort_order' => 3],
        ['key' => 'linkedin', 'name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'hover_class' => 'hover:bg-primary-700', 'sort_order' => 4],
        ['key' => 'youtube', 'name' => 'YouTube', 'icon' => 'fab fa-youtube', 'hover_class' => 'hover:bg-red-600', 'sort_order' => 5],
        ['key' => 'discord', 'name' => 'Discord', 'icon' => 'fab fa-discord', 'hover_class' => 'hover:bg-purple-600', 'sort_order' => 6],
        ['key' => 'telegram', 'name' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'hover_class' => 'hover:bg-primary-900', 'sort_order' => 7],
    ];

    public function up(): void
    {
        Schema::create('social_media_links', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('icon');
            $table->string('hover_class')->nullable();
            $table->text('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        foreach ($this->links as $link) {
            DB::table('social_media_links')->insert([
                'key' => $link['key'],
                'name' => $link['name'],
                'icon' => $link['icon'],
                'hover_class' => $link['hover_class'],
                'sort_order' => $link['sort_order'],
                'url' => null,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('social_media_links');
    }
};
