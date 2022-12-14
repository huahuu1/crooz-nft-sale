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
        Schema::create('xeno_class_sale_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('nft_auction_infos');
            $table->foreignId('xeno_class_id')->constrained('xeno_classes');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
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
        Schema::dropIfExists('xeno_class_sale_times');
    }
};