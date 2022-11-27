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
        Schema::create('transaction_raw_datas', function (Blueprint $table) {
            $table->id();
            $table->string('chain', 50);
            $table->char('tx_hash', 150);
            $table->string('from', 150);
            $table->string('to', 150);
            $table->string('token', 100);
            $table->decimal('value', 30, 10);
            $table->timestamp('timestamp');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_raws');
    }
};