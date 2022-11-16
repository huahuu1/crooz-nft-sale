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
        Schema::create('nft_auction_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('nft_auction_packages');
            $table->foreignId('nft_id')->constrained('nfts', 'nft_id');
            $table->integer('ticket_quantity');
            $table->integer('nft_quantity');
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
        Schema::dropIfExists('nft_auction_rewards');
    }
};
