<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_cash_flows', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('cash_flow_id')->constrained('cash_flows');
            $table->foreignId('entity_id', 'nft_deposits')->constrained('nft_deposits');
            $table->foreign('entity_id', 'withdrawals')->references('id')->on('withdrawals');
            $table->char('entity_type', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_cash_flows');
    }
}
