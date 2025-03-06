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
        Schema::create('nenkis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('new_user', 32)->nullable()->comment('登録ユーザー');
            $table->string('upd_user', 32)->nullable()->comment('更新ユーザー');
            $table->integer('kakocho_id')->unsigned()->comment('過去帳ID');
            $table->integer('kaiki_id')->unsigned()->comment('回忌ID');
            $table->dateTime('houyou_date')->comment('法要日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nenkis');
    }
};
