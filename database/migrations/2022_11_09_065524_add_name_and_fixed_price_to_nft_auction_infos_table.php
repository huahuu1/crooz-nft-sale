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
        Schema::table('nft_auction_infos', function (Blueprint $table) {
            $table->decimal('fixed_price', 30, 10)->after('status')->nullable();
            $table->string('name', 50)->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nft_auction_infos', function (Blueprint $table) {
            $table->dropColumn('fixed_price');
            $table->dropColumn('name');
        });
    }
};
