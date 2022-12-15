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
        Schema::table('nft_auction_rewards', function (Blueprint $table) {
            $table->integer('nft_id')->nullable()->change();
            $table->integer('ticket_quantity')->nullable()->change();
            $table->integer('nft_quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nft_auction_rewards', function (Blueprint $table) {
            $table->integer('nft_id')->change();
            $table->integer('ticket_quantity')->change();
            $table->integer('nft_quantity')->change();
        });
    }
};