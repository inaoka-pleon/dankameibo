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
        Schema::create('payment_slips', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('accountno1', 5)->nullable()->comment('口座番号１');
            $table->string('accountno2', 1)->nullable()->comment('口座番号２');
            $table->string('accountno3', 7)->nullable()->comment('口座番号３');
            $table->string('name', 40)->nullable()->comment('加入者名');
            $table->integer('price')->nullable()->comment('金額');
            $table->integer('jiin_id')->unsigned()->comment('寺院ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_slips');
    }
};
