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
            $table->dropForeign(['token_sale_id']);
            $table->dropColumn('token_sale_id');
            $table->dropColumn('amount_lock_remain');
            $table->dropColumn('current_order_unlock');
            $table->renameColumn('amount_lock', 'token_unlock_volume');
            $table->renameColumn('next_run_date', 'unlock_date');
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
            $table->foreignId('token_sale_id')->constrained('token_sale_infos');
            $table->decimal('amount_lock_remain', 30, 10);
            $table->integer('current_order_unlock')->default(0);
            $table->renameColumn('token_unlock_volume', 'amount_lock');
            $table->renameColumn('unlock_date', 'next_run_date');
        });
    }
};
