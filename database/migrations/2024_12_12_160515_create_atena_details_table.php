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
        Schema::create('atena_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 40)->nullable()->comment('氏名');
            $table->string('namekana')->nullable()->comment('氏名かな');
            $table->string('keishou')->nullable()->comment('敬称');
            $table->string('postcode', 10)->nullable()->comment('郵便番号');
            $table->string('address1')->nullable()->comment('住所１');
            $table->string('address2')->nullable()->comment('住所２');
            $table->string('postcard')->nullable()->comment('はがき');
            $table->integer('atena_header_id')->comment('宛名一覧ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atena_details');
    }
};
