<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('coin_id')->constrained('coins');
            $table->char('tx_hash', 150);
            $table->boolean('type')->default(1);
            $table->enum('transaction_type', ['TOKEN_WITHDRAWAL', 'TOKEN_DEPOSIT', 'NFT_DEPOSIT']);
            $table->decimal('amount', 12, 10);
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
        Schema::dropIfExists('cash_flows');
    }
}
