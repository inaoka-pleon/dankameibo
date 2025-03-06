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
        Schema::create('nenkilists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('kuyou')->nullable()->comment('供養');
            $table->string('memo')->nullable()->comment('備考');
            $table->integer('kakocho_id')->comment('過去帳ID');
            $table->integer('kaiki_id')->comment('回忌ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nenkilists');
    }
};
