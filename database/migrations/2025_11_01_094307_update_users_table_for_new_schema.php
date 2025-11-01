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
        Schema::table('users', function (Blueprint $table) {
            // 🔹 إضافة الحقول الجديدة إن لم تكن موجودة
            // if (!Schema::hasColumn('users', 'type')) {
            //     // $table-/>string('account_type', 20)->default('student')->after('id');
            // }

            // // 🔹 تعديل بعض الأعمدة القديمة (اختياري)
            // if (Schema::hasColumn('users', 'type')) {
            //     $table->renameColumn('type', 'legacy_type');
            // }

            // 🟢 تعديل الحقول القديمة لتكون nullable
            if (Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->change();
            }

            if (Schema::hasColumn('users', 'slug')) {
                $table->string('slug')->nullable()->change();
            }

            if (Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->change();
            }
            // 🔹 تأكد من وجود deleted_at (في حال soft deletes)
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // if (Schema::hasColumn('users', 'account_type')) {
            //     $table->dropColumn('account_type');
            // }

            if (Schema::hasColumn('users', 'legacy_type')) {
                $table->renameColumn('legacy_type', 'type');
            }
        });
    }
};
