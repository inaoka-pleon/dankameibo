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
        Schema::create('nenki_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('document1', 36)->nullable()->comment('文書１');
            $table->string('document2', 36)->nullable()->comment('文書２');
            $table->string('document3', 36)->nullable()->comment('文書３');
            $table->string('document4', 36)->nullable()->comment('文書４');
            $table->string('document5', 36)->nullable()->comment('文書５');
            $table->string('document6', 36)->nullable()->comment('文書６');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nenki_documents');
    }
};
