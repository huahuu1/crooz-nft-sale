<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_unlock_balances', function (Blueprint $table) {
            $table->dateTime('next_run_date')->nullable()->after('amount_lock_remain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_unlock_balances', function (Blueprint $table) {
            $table->dropColumn('next_run_date');
        });
    }
};
