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
        Schema::create('dankas', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable()->comment('地区名');
            $table->string('dankadivision')->nullable()->comment('檀家区分');
            $table->string('mortuarytablet')->nullable()->comment('位牌区分');
            $table->integer('gozikai')->nullable()->default(0)->comment('護持会');
            $table->integer('membershipfee')->nullable()->default(0)->comment('会費');
            $table->integer('report')->nullable()->default(0)->comment('届出');
            $table->integer('tanagyou')->nullable()->default(0)->comment('棚経');
            $table->integer('haruhigan')->nullable()->default(0)->comment('春彼岸');
            $table->integer('akihigan')->nullable()->default(0)->comment('秋彼岸');
            $table->integer('hanamatsuri')->nullable()->default(0)->comment('花まつり');
            $table->integer('division1')->nullable()->comment('区分1');
            $table->integer('division2')->nullable()->comment('区分2');
            $table->integer('division3')->nullable()->comment('区分3');
            $table->integer('division4')->nullable()->comment('区分4');
            $table->integer('division5')->nullable()->comment('区分5');
            $table->integer('division6')->nullable()->comment('区分6');
            $table->integer('postcard')->nullable()->comment('はがき区分');
            $table->string('memo')->nullable()->comment('備考');
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
        Schema::dropIfExists('dankas');
    }
};
