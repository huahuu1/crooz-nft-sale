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
        Schema::create('token_unlock_rules', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('rule_code');
            $table->integer('period');
            $table->char('unit', 20);
            $table->decimal('unlock_percentages', 10, 6);
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
        Schema::dropIfExists('token_unlock_rules');
    }
};
