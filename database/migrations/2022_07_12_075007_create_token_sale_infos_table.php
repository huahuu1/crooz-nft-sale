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
        Schema::create('token_sale_infos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('lock_id')->constrained('lock_infos');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('total');
            $table->decimal('price', 20, 15);
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
        Schema::dropIfExists('token_sale_infos');
    }
};
