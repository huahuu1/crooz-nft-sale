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
        Schema::create('ticket_used_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gacha_ticket_id')->constrained('gacha_tickets');
            $table->integer('used_quantity');
            $table->dateTime('used_time');
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
        Schema::dropIfExists('ticket_used_histories');
    }
};
