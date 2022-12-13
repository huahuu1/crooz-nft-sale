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
        Schema::create('nft_classes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('sale_date');
            $table->foreignId('auction_id')->constrained('nft_auction_infos');
            $table->foreignId('package_id')->constrained('nft_auction_packages');
            $table->unsignedBigInteger('xeno_class');
            $table->foreign('xeno_class')->references('nft_id')->on('nfts');
            $table->integer('xeno_gacha_id');
            $table->integer('weapon_gacha_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nft_classes');
    }
};