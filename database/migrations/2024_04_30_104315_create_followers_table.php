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
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->comment('氏名');
            $table->string('namekana')->comment('氏名かな');
            $table->string('relationship')->nullable()->comment('続柄');
            $table->date('birthdate')->nullable()->comment('生年月日');
            $table->string('gender')->nullable()->comment('性別');
            $table->string('position')->nullable()->comment('寺役職');
            $table->string('postcode', 10)->nullable()->comment('郵便番号');
            $table->string('address1')->nullable()->comment('住所１');
            $table->string('address2')->nullable()->comment('住所２');
            $table->string('tel', 15)->nullable()->comment('TEL');
            $table->string('fax', 15)->nullable()->comment('FAX');
            $table->string('occupation')->nullable()->comment('職業');
            $table->string('seizenkaimyou')->nullable()->comment('生前戒名');
            $table->string('kaimyou')->nullable()->comment('戒名');
            $table->string('zokumyou', 40)->nullable()->comment('俗名');
            $table->string('zokumyoukana')->nullable()->comment('俗名ふりがな');
            $table->date('deathanniversary')->comment('命日');
            $table->integer('death_era')->unsigned()->comment('命日(元号)');
            $table->integer('death_year')->comment('命日(和暦年)');
            $table->integer('death_month')->comment('命日(月)');
            $table->integer('death_day')->comment('命日(日)');
            $table->string('kakocho_memo')->nullable()->comment('過去帳メモ');
            $table->integer('ageatdeath')->nullable()->comment('行年');
            $table->integer('danka_id')->comment('檀家ID');
            $table->integer('chiefmourner_flg')->default(0)->comment('施主フラグ');
            $table->integer('deceased_flg')->default(0)->comment('故人フラグ');
            $table->string('memo')->nullable()->comment('備考');
            $table->integer('jiin_id')->unsigned()->comment('寺院ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
};
