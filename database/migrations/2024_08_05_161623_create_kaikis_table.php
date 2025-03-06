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
        Schema::create('kaikis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('new_user', 32)->nullable()->comment('登録ユーザー');
            $table->string('upd_user', 32)->nullable()->comment('更新ユーザー');
            $table->string('kaiki_kbn', 1)->comment('回忌区分(0:回忌 1:百箇日 2:初花 3:初盆)');
            $table->integer('kaiki')->nullable()->comment('回忌');
            $table->string('kaiki_name', 10)->comment('回忌名');
            $table->integer('target_flg')->default(1)->comment('対象フラグ');
            $table->string('from_year_kbn', 1)->nullable()->comment('開始年区分(0:前年 1:今年)');
            $table->integer('from_month')->nullable()->comment('開始月');
            $table->integer('from_day')->nullable()->comment('開始日');
            $table->string('to_year_kbn', 1)->nullable()->comment('終了年区分(0:前年 1:今年)');
            $table->integer('to_month')->nullable()->comment('終了月');
            $table->integer('to_day')->nullable()->comment('終了日');
            $table->integer('houyou_month')->nullable()->comment('法要月');
            $table->integer('houyou_day')->nullable()->comment('法要日');
            $table->integer('disp_order')->comment('表示順');
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
        Schema::dropIfExists('kaikis');
        //
    }
};
