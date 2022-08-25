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
        Schema::table('token_sale_infos', function (Blueprint $table) {
            $table->decimal('price', 30, 10)->change();
        });
        Schema::table('nft_auction_infos', function (Blueprint $table) {
            $table->decimal('min_price', 30, 10)->change();
        });
        Schema::table('token_sale_histories', function (Blueprint $table) {
            $table->decimal('amount', 30, 10)->change();
        });
        Schema::table('nft_auction_histories', function (Blueprint $table) {
            $table->decimal('amount', 30, 10)->change();
        });
        Schema::table('user_balances', function (Blueprint $table) {
            $table->decimal('amount_total', 30, 10)->change();
            $table->decimal('amount_lock', 30, 10)->change();
        });
        Schema::table('user_withdrawals', function (Blueprint $table) {
            $table->decimal('amount', 30, 10)->change();
        });
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->decimal('amount', 30, 10)->change();
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
            $table->decimal('price', 20, 15)->change();
        });
        Schema::table('nft_auction_infos', function (Blueprint $table) {
            $table->decimal('min_price', 20, 15)->change();
        });
        Schema::table('token_sale_histories', function (Blueprint $table) {
            $table->decimal('amount', 20, 15)->change();
        });
        Schema::table('nft_auction_histories', function (Blueprint $table) {
            $table->decimal('amount', 20, 15)->change();
        });
        Schema::table('user_balances', function (Blueprint $table) {
            $table->decimal('amount_total', 20, 15)->change();
            $table->decimal('amount_lock', 20, 15)->change();
        });
        Schema::table('user_withdrawals', function (Blueprint $table) {
            $table->decimal('amount', 20, 15)->change();
        });
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->decimal('amount', 20, 15)->change();
        });
    }
};
