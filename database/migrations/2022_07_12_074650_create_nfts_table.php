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
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained('users');
            $table->char('nft_token_id', 100);
            $table->string('name', 100);
            $table->text('storage')->nullable();
            $table->enum('status', ['GENERAL', 'CLOSE', 'WALLET', 'WITHDRAWAL', 'SELLING']);
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
