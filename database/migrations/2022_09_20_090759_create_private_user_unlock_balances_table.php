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
        Schema::create('private_user_unlock_balances', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('token_id')->constrained('token_masters');
            $table->foreignId('token_sale_id')->constrained('token_sale_infos');
            $table->string('wallet_address', 150);
            $table->decimal('amount_lock', 30, 10);
            $table->decimal('amount_lock_remain', 30, 10);
            $table->dateTime('next_run_date')->nullable();
            $table->integer('current_order_unlock')->default(0);
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
        Schema::dropIfExists('private_user_unlock_balances');
    }
};
