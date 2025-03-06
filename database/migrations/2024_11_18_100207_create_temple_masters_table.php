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
        Schema::create('temple_masters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mountainname')->nullable()->comment('山号');
            $table->string('templename')->nullable()->comment('寺院名');
            $table->string('jyushokuname', 40)->nullable()->comment('住職氏名');
            $table->string('postcode', 10)->nullable()->comment('郵便番号');
            $table->string('address1')->nullable()->comment('住所１');
            $table->string('address2')->nullable()->comment('住所２');
            $table->string('tel', 15)->nullable()->comment('電話番号');
            $table->string('fax', 15)->nullable()->comment('FAX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temple_masters');
    }
};
