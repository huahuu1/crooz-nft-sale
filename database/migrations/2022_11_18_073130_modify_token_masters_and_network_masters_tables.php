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
        Schema::table('network_masters', function (Blueprint $table) {
            $table->dropColumn('contract_wallet');
        });

        Schema::table('token_masters', function (Blueprint $table) {
            $table->string('contract_wallet', 150)->after('network_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network_masters', function (Blueprint $table) {
            $table->string('contract_wallet', 150)->after('unit')->nullable();
        });

        Schema::table('token_masters', function (Blueprint $table) {
            $table->dropColumn('contract_wallet');
        });
    }
};
