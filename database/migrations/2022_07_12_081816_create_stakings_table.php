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
        Schema::create('stakings', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('coin_id')->constrained('coins');
            $table->foreignId('lock_time_id')->constrained('lock_times');
            $table->decimal('amount', 12, 10);
            $table->enum('type', ['STAKING', 'AUCTION']);
            $table->enum('status', ['REQUESTING', 'PROCESSING', 'CLOSE', 'FORCECLOSE']);
            $table->decimal('interest', 12, 10)->nullable();
            $table->string('dividend', 150);
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
        Schema::dropIfExists('stakings');
    }
};
