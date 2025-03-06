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
        Schema::create('generics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('key1', 20)->comment('KEY1');
            $table->string('key2', 20)->nullable()->comment('KEY2');
            $table->string('key3', 20)->nullable()->comment('KEY3');
            $table->string('value1', 40)->comment('VALUE1');
            $table->string('value2', 40)->nullable()->comment('VALUE2');
            $table->string('value3', 40)->nullable()->comment('VALUE3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generics');
    }
};
