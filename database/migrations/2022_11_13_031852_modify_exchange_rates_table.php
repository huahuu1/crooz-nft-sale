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
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->char('symbol', 6)->index()->change();
            $table->boolean('status')->default(1)->index()->after('rate');
            $table->dateTime('rate_timestamp')->useCurrent()->index()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->string('symbol', 20)->change();
            $table->dropColumn('status');
            $table->dropColumn('rate_timestamp');
        });
    }
};
