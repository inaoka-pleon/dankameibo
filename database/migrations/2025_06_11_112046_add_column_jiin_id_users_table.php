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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('jiin_id')->unsigned()->comment('寺院ID');
            $table->string('name_kana')->nullable()->comment('ユーザー名かな');
            $table->string('memo')->nullable()->comment('備考');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('jiin_id');
            $table->dropColumn('name_kana');
            $table->dropColumn('memo');
        });
        //
    }
};
