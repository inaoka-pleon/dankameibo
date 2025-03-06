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
        Schema::create('akihigan_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 40)->nullable()->comment('氏名');
            $table->string('namekana')->nullable()->comment('氏名かな');
            $table->string('postcode', 10)->nullable()->comment('郵便番号');
            $table->string('address1')->nullable()->comment('住所１');
            $table->string('address2')->nullable()->comment('住所２');
            $table->string('tel', 15)->nullable()->comment('電話番号');
            $table->integer('month')->nullable()->comment('月');
            $table->integer('day')->nullable()->comment('日');
            $table->string('ampm')->nullable()->comment('午前午後');
            $table->integer('hour')->nullable()->comment('時');
            $table->string('minute')->nullable()->comment('分');
            $table->string('manager')->nullable()->comment('担当者');
            $table->integer('danka_id')->comment('檀家ID');
            $table->integer('akihigan_header_id')->comment('秋彼岸一覧ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akihigan_details');
    }
};
