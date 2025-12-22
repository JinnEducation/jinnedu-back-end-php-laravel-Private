<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
             $table->index(['from_user', 'to_user', 'id'], 'chats_from_to_id_idx');
            $table->index(['to_user', 'seen'], 'chats_to_seen_idx');
        });

        Schema::table('chat_contacts', function (Blueprint $table) {
            $table->unique(['user_id', 'contact_id'], 'chat_contacts_user_contact_unique');
            $table->index(['user_id', 'last_msg_date'], 'chat_contacts_user_lastdate_idx');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
             $table->dropIndex('chats_from_to_id_idx');
            $table->dropIndex('chats_to_seen_idx');
        });

        Schema::table('chat_contacts', function (Blueprint $table) {
            $table->dropUnique('chat_contacts_user_contact_unique');
            $table->dropIndex('chat_contacts_user_lastdate_idx');
        });
        
    }
};
