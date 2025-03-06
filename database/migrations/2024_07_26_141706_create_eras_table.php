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
        Schema::create('eras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 4)->comment('元号');
            $table->integer('years')->comment('年数');
            $table->integer('ad_start')->comment('開始西暦');
            $table->date('start_ymd')->comment('開始年月日');
            $table->date('end_ymd')->comment('終了年月日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eras');
    }
};
