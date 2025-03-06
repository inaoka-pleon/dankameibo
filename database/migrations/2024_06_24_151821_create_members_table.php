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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('qualification')->nullable()->comment('資格');
            $table->string('name', 40)->comment('氏名');
            $table->string('namekana')->comment('氏名かな');
            $table->string('title')->nullable()->comment('敬称');
            $table->string('subtitle')->nullable()->comment('脇敬称');
            $table->string('postcode', 10)->nullable()->comment('郵便番号');
            $table->string('address1')->comment('住所１');
            $table->string('address2')->nullable()->comment('住所２');
            $table->string('tel', 15)->comment('電話番号');
            $table->string('fax', 15)->nullable()->comment('FAX');
            $table->string('letterdivision')->nullable()->comment('手紙区分');
            $table->string('newyearscarddivision')->nullable()->comment('年賀状区分');
            $table->string('summergreetingdivision')->nullable()->comment('暑中見舞区分');
            $table->string('relationshiop')->nullable()->comment('関係');
            $table->string('teacher')->nullable()->comment('師');
            $table->string('memo')->nullable()->comment('備考');
            $table->integer('temple_id')->comment('寺院ID');
            $table->integer('chiefpriest_flg')->default(0)->comment('住職フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
