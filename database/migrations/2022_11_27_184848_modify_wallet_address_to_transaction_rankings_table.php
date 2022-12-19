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
        Schema::table('transaction_rankings', function (Blueprint $table) {
            $table->dropUnique(['wallet_address']);
            $table->char('tx_hash', 150)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_rankings', function (Blueprint $table) {
            $table->dropUnique(['wallet_address', 'tx_hash']);
        });
    }
};
