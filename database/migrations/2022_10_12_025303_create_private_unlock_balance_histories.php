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
        Schema::create('private_unlock_balance_histories', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('unlock_id')->constrained('private_user_unlock_balances');
            $table->decimal('amount', 30, 10);
            $table->dateTime('release_token_date');
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
        Schema::dropIfExists('private_unlock_balance_histories');
    }
};
