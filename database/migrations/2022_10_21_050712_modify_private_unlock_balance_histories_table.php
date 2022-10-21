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
        Schema::table('private_unlock_balance_histories', function (Blueprint $table) {
            $table->renameColumn('release_token_date', 'unlock_token_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('private_unlock_balance_histories', function (Blueprint $table) {
            $table->renameColumn('unlock_token_date', 'release_token_date');
        });
    }
};
