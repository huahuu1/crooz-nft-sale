<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_nfts', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_address', 150)->nullable();
            $table->unsignedBigInteger('nft_id');
            $table->foreign('nft_id')->references('nft_id')->on('nfts');
            $table->foreignId('nft_auction_id')->nullable()->constrained('nft_auction_infos');
            $table->boolean('status')->nullable()->default(1);
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
        Schema::dropIfExists('auction_nfts');
    }
};
