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
            $table->dropForeign(['admin_id']);
            $table->dropIndex('private_unlock_balance_histories_admin_id_foreign');
            $table->unsignedBigInteger('admin_id')->nullable()->change();
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
            $table->foreignId('admin_id')->after('unlock_token_date')->constrained('admins');
        });
    }
};
