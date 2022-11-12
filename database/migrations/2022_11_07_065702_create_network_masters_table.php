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
        Schema::create('network_masters', function (Blueprint $table) {
            $table->id();
            $table->string('chain_id', 10);
            $table->string('rpc_urls');
            $table->string('block_explorer_urls');
            $table->string('chain_name');
            $table->string('unit', 10);
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
        Schema::dropIfExists('network_masters');
    }
};
