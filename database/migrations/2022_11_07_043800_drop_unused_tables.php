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
        Schema::disableForeignKeyConstraints();
        Schema::drop('token_sale_histories');
        Schema::drop('token_sale_infos');
        Schema::drop('token_unlock_rules');
        Schema::drop('unlock_balance_histories');
        Schema::drop('user_unlock_balances');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('token_unlock_rules', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('rule_code');
            $table->integer('period');
            $table->char('unit', 20);
            $table->decimal('unlock_percentages', 10, 6);
            $table->timestamps();
        });

        Schema::create('token_sale_infos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('rule_id')->after('id')->constrained('token_unlock_rules');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('total');
            $table->float('total_supply')->nullable();
            $table->decimal('price', 30, 10);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('token_sale_histories', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('token_id')->constrained('token_masters');
            $table->foreignId('token_sale_id')->constrained('token_sale_infos');
            $table->decimal('amount', 30, 10);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAIL']);
            $table->char('tx_hash', 150);
            $table->timestamps();
        });

        Schema::create('user_unlock_balances', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('token_id')->constrained('token_masters');
            $table->foreignId('token_sale_id')->constrained('token_sale_infos');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('amount_lock', 30, 10);
            $table->decimal('amount_lock_remain', 30, 10);
            $table->dateTime('next_run_date')->nullable();
            $table->integer('current_order_unlock')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('unlock_balance_histories', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('unlock_id')->constrained('user_unlock_balances');
            $table->decimal('amount', 30, 10);
            $table->dateTime('release_token_date');
            $table->timestamps();
        });
    }
};
