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
        Schema::create('general_postcards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable()->comment('表題');
            $table->string('document1', 32)->nullable()->comment('文書１');
            $table->string('document2', 32)->nullable()->comment('文書２');
            $table->string('document3', 32)->nullable()->comment('文書３');
            $table->string('document4', 32)->nullable()->comment('文書４');
            $table->string('document5', 32)->nullable()->comment('文書５');
            $table->string('document6', 32)->nullable()->comment('文書６');
            $table->string('document7', 32)->nullable()->comment('文書７');
            $table->string('document8', 32)->nullable()->comment('文書８');
            $table->string('document9', 32)->nullable()->comment('文書９');
            $table->string('document10', 32)->nullable()->comment('文書１０');
            $table->string('document11', 32)->nullable()->comment('文書１１');
            $table->string('document12', 32)->nullable()->comment('文書１２');
            $table->string('kakui')->nullable()->comment('各位');
            $table->string('templename')->nullable()->comment('寺院名');
            $table->string('address')->nullable()->comment('住所');
            $table->string('tel')->nullable()->comment('電話番号');
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
        Schema::dropIfExists('general_postcards');
    }
};
