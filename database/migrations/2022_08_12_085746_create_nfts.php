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
        Schema::create('nfts', function (Blueprint $table) {
            $table->id();
            $table->char('serial_no', 30);
            $table->foreignId('type_id')->constrained('nft_types');
            $table->char('nft_id', 30);
            $table->foreignId('nft_owner_id')->constrained('users');
            $table->char('tx_hash', 150)->nullable();
            $table->string('image_url', 255);
            $table->boolean('status');
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
        Schema::dropIfExists('nfts');
    }
};
