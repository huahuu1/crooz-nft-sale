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
        Schema::table('token_sale_infos', function (Blueprint $table) {
            $table->dropForeign(['lock_id']);
            $table->dropColumn('lock_id');
            $table->foreignId('rule_id')->after('id')->constrained('token_unlock_rules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('token_sale_infos', function (Blueprint $table) {
            $table->dropForeign(['rule_id']);
            $table->dropColumn('rule_id');
            $table->foreignId('lock_id')->after('id')->constrained('lock_infos');
        });
    }
};
