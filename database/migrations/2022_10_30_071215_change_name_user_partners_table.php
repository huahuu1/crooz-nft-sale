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
        Schema::table('user_partners', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::rename('user_partners', 'gxe_partner_users');

        Schema::table('gxe_partner_users', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gxe_partner_users', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::rename('gxe_partner_users', 'user_partners');

        Schema::table('user_partners', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users');
        });
    }
};
