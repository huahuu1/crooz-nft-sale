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
        Schema::table('nft_auction_rewards', function (Blueprint $table) {
            $table->foreignId('nft_delivery_id')->nullable()->after('nft_id')->constrained('nft_delivery_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nft_auction_rewards', function (Blueprint $table) {
            $table->dropForeign(['nft_delivery_id']);
            $table->dropColumn('nft_delivery_id');
        });
    }
};
