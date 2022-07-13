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
        Schema::create('other_transactions', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained('users');
            $table->char('tx_hash', 150);
            $table->enum('transaction_type', ['TOKEN_WITHDRAWAL', 'TOKEN_DEPOSIT', 'NFT_DEPOSIT']);
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
        Schema::dropIfExists('other_transactions');
    }
};
