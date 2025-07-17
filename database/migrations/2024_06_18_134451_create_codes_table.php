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
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('key1');
            $table->string('key2');
            $table->string('key3');
            $table->string('value1');
            $table->string('value2')->nullable();
            $table->integer('jiin_id')->unsigned()->nullable()->comment('寺院ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codes');
    }
};
