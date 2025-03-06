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
        Schema::create('haruhigan_headers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('era')->comment('元号');
            $table->string('year')->comment('年数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('haruhigan_headers');
    }
};
