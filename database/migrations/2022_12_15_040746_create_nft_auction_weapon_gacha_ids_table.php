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
        Schema::create('nft_auction_weapon_gacha_ids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nft_id');
            $table->foreign('nft_id')->references('nft_id')->on('nfts');
            $table->integer('weapon_gacha_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nft_auction_weapon_gacha_ids');
    }
};