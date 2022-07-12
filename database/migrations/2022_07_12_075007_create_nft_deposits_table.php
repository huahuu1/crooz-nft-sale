<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNftDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nft_deposits', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('nft_id')->constrained('nfts');
            $table->boolean('type')->default(1);
            $table->enum('status', ['PROCESSING', 'CLOSE', 'FORCECLOSE']);
            $table->char('from_wallet', 100);
            $table->char('to_wallet', 100);
            $table->char('tx_hash', 150);
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
        Schema::dropIfExists('nft_deposits');
    }
}
