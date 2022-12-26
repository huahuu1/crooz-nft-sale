<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE nft_auction_histories CHANGE COLUMN payment_method payment_method ENUM('CRYPTO', 'CREDIT', 'COUPON') NULL");
        DB::statement("ALTER TABLE nft_auction_histories CHANGE COLUMN status status ENUM('PENDING', 'SUCCESS', 'FAIL', 'CANCELED') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE nft_auction_histories CHANGE COLUMN payment_method payment_method ENUM('CRYPTO', 'CREDIT') NULL");
        DB::statement("ALTER TABLE nft_auction_histories CHANGE COLUMN status status ENUM('PENDING', 'SUCCESS', 'FAIL') NOT NULL");
    }
};
