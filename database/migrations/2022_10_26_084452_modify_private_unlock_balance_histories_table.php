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
        Schema::table('private_unlock_balance_histories', function (Blueprint $table) {
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAIL'])->after('admin_id');
            $table->char('tx_hash', 150)->after('admin_id');
            $table->integer('network_id')->after('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('private_unlock_balance_histories', function (Blueprint $table) {
            $table->dropColumn('status', ['PENDING', 'SUCCESS', 'FAIL']);
            $table->dropColumn('tx_hash', 150);
            $table->dropColumn('network_id');
        });
    }
};
