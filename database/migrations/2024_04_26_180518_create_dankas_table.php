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
            $table->integer('postcard')->nullable()->comment('はがき区分');
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
        Schema::dropIfExists('dankas');
    }
};
