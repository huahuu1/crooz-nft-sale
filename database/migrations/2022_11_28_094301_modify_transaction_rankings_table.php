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
            $table->dropColumn('tx_hash');
        });

        Schema::rename('transaction_rankings', 'auction_rankings');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_rankings', function (Blueprint $table) {
            $table->char('tx_hash', 150);
        });

        Schema::rename('auction_rankings', 'transaction_rankings');
    }
};
