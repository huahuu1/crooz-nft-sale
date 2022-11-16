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
        Schema::create('nft_auction_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('nft_auction_infos');
            $table->decimal('price', 30, 10)->nullable();
            $table->decimal('unit_price', 30, 10)->nullable();
            $table->string('destination_address', 150);
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
        Schema::dropIfExists('nft_auction_packages');
    }
};
