<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Menu;
use Bouncer;
use Illuminate\Database\Seeder;

class AdminChatMonitorPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $chatMenu = Menu::query()
            ->where('route', 'chats')
            ->orWhere('name', 'chats')
            ->first();

        if ($chatMenu) {
            Menu::query()->updateOrCreate(
                [
                    'route' => 'chats.admin-chat-monitor',
                ],
                [
                    'invisible' => 1,
                    'type' => Chat::class,
                    'title' => 'admin-chat-monitor',
                    'p_id' => $chatMenu->id,
                    'name' => 'admin-chat-monitor',
                    'active_routes' => 'chats.admin-chat-monitor',
                    'svg' => '',
                ]
            );
        }

        foreach (['admin', 'superadmin'] as $roleName) {
            if (Bouncer::role()->where('name', $roleName)->exists()) {
                Bouncer::allow($roleName)->to('admin-chat-monitor', Chat::class);
            }
        }
    }
}
