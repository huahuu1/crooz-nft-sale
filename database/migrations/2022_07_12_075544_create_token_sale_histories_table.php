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
        Schema::create('token_sale_histories', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('token_id')->constrained('token_masters');
            $table->foreignId('token_sale_id')->constrained('token_sale_infos');
            $table->decimal('amount', 20, 15);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAIL']);
            $table->char('tx_hash', 150);
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
        Schema::dropIfExists('token_sale_histories');
    }
};
