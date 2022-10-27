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
        Schema::table('private_user_unlock_balances', function (Blueprint $table) {
            $table->integer('token_type')->after('token_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('private_user_unlock_balances', function (Blueprint $table) {
            $table->dropColumn('token_type');
        });
    }
};
