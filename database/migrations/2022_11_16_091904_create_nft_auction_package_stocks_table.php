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
        Schema::create('nft_auction_package_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('nft_auction_packages');
            $table->integer('total')->unsigned()->default(0);
            $table->integer('remain')->default(0);
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
        Schema::dropIfExists('nft_auction_package_stocks');
    }
};
