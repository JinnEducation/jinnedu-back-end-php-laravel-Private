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
    public function up() : void
    {
        if (!Schema::hasColumn('tutor_finances', 'total')) {
        Schema::table('tutor_finances', function (Blueprint $table) {
            $table->decimal('total', 8, 2)->default(0)->after('ref_id');
        });
    }
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutor_finances', function (Blueprint $table) {
            $table->dropColumn('total');
        });
    }
};
