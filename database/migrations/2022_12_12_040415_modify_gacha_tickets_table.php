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
        Schema::table('gacha_tickets', function (Blueprint $table) {
            $table->foreignId('nft_auction_id')->nullable()->after('user_id')->constrained('nft_auction_infos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gacha_tickets', function (Blueprint $table) {
            $table->dropForeign(['nft_auction_id']);
            $table->dropColumn('nft_auction_id');
        });
    }
};
