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
        Schema::create('user_unlock_balances', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('token_id')->constrained('token_masters');
            $table->foreignId('token_sale_id')->constrained('token_sale_infos');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('amount_lock', 30, 10);
            $table->decimal('amount_lock_remain', 30, 10);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('user_unlock_balances');
    }
};
