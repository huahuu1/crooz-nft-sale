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
        Schema::table('xeno_classes', function (Blueprint $table) {
            $table->string('standard_img')->nullable()->after('class');
            $table->string('special_img')->nullable()->after('standard_img');
            $table->string('premium_img')->nullable()->after('special_img');
            $table->string('legandary_img')->nullable()->after('premium_img');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xeno_classes', function (Blueprint $table) {
            $table->dropColumn(['standard_img', 'special_img', 'premium_img', 'legandary_img']);
        });
    }
};
