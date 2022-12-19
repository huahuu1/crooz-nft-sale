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
        Schema::create('nft_auction_gacha_ids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('nft_auction_packages');
            $table->foreignId('sale_time_id')->constrained('xeno_class_sale_times');
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
        Schema::dropIfExists('nft_auction_gacha_ids');
    }
};
