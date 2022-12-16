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
        Schema::rename('nft_auction_gacha_ids', 'nft_auction_xeno_gacha_ids');
        Schema::table('nft_auction_xeno_gacha_ids', function (Blueprint $table) {
            $table->dropColumn(['weapon_gacha_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('nft_auction_xeno_gacha_ids', 'nft_auction_gacha_ids');

        Schema::table('nft_auction_gacha_ids', function (Blueprint $table) {
            $table->integer('weapon_gacha_id');
        });
    }
};
