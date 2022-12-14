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


        DB::statement("ALTER TABLE cash_flows CHANGE COLUMN payment_method payment_method ENUM('CRYPTO', 'CREDIT', 'COUPON') NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE cash_flows CHANGE COLUMN payment_method payment_method ENUM('CRYPTO', 'CREDIT') NULL");
    }
};