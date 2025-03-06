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
        Schema::create('temples', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('templeoffice')->nullable()->comment('宗務所');
            $table->string('parish')->nullable()->comment('教区');
            $table->integer('no')->nullable()->comment('寺籍番号');
            $table->string('templename')->nullable()->comment('寺院名');
            $table->string('templenamekana')->nullable()->comment('寺院名かな');
            $table->string('mountainname')->nullable()->comment('山号');
            $table->string('jikaku')->nullable()->comment('寺格');
            $table->string('buddhistfederation')->nullable()->comment('仏教会');
            $table->string('memo')->nullable()->comment('備考');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temples');
    }
};
