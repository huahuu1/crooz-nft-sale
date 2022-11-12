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
        Schema::table('nft_auction_histories', function (Blueprint $table) {
            $table->enum('payment_method', ['CRYPTO', 'CREDIT'])->after('tx_hash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nft_auction_histories', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
