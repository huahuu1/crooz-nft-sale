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
        Schema::table('user_withdrawals', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('user_withdrawals', function (Blueprint $table) {
            $table->enum('status', ['WAITING', 'OPEN', 'PROCESSING', 'SUCCESS', 'FAIL'])
                  ->after('request_time');
            $table->foreignId('private_unlock_id')->after('token_id')->constrained('private_user_unlock_balances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_withdrawals', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('user_withdrawals', function (Blueprint $table) {
            $table->enum('status', ['REQUESTING', 'PROCESSING', 'CLOSE', 'FORCECLOSE', 'REJECT'])
                  ->after('request_time');
            $table->dropForeign(['private_unlock_id']);
            $table->dropColumn('private_unlock_id');
        });
    }
};
