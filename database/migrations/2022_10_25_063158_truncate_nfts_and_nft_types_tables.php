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
        DB::table('nfts')->truncate();
        DB::table('nft_types')->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('nfts', function (Blueprint $table) {
            $table->unsignedBigInteger('nft_id')->autoIncrement();
            $table->foreignId('nft_type')->constrained('nft_types');
            $table->string('name');
            $table->string('image_url');
            $table->boolean('status')->nullable()->default(1);
            $table->timestamps();
        });

        Schema::create('nft_types', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name', 100);
            $table->boolean('status');
            $table->timestamps();
        });
    }
};
