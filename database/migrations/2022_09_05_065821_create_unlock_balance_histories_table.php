<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unlock_balance_histories', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('unlock_id')->constrained('user_unlock_balances');
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
        Schema::dropIfExists('unlock_balance_histories');
    }
};
