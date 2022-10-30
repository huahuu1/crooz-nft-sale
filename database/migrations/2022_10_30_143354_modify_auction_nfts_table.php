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
        DB::table('auction_nfts')->truncate();
        Schema::table('auction_nfts', function (Blueprint $table) {
            $table->dropForeign(['nft_auction_id']);
            $table->dropColumn(['nft_auction_id']);
            $table->foreignId('nft_delivery_source_id')->after('nft_id')->constrained('nft_delivery_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_nfts', function (Blueprint $table) {
            $table->dropForeign(['nft_delivery_source_id']);
            $table->dropColumn(['nft_delivery_source_id']);
            $table->foreignId('nft_auction_id')->after('nft_id')->constrained('auction_nfts');
        });
    }
};
