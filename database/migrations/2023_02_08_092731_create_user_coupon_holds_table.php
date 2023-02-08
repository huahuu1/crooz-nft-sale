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
        Schema::create('user_coupon_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_coupon_id')->constrained('user_coupons');
            $table->foreignId('package_id')->constrained('nft_auction_packages');
            $table->dateTime('purchased_time');
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
        Schema::dropIfExists('user_coupon_holds');
    }
};
