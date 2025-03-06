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
        Schema::create('gozikaikaihi_lists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('payment_date')->nullable()->comment('入金日');
            $table->string('payment_class')->nullable()->comment('入金区分');
            $table->string('deposit_amount')->nullable()->comment('入金額');
            $table->integer('danka_id')->comment('檀家ID');
            $table->string('memo')->nullable()->comment('備考');
            $table->date('target_year')->nullable()->comment('対象年度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gozikaikaihi_lists');
    }
};
