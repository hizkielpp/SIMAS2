<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            // Foreign key tabel surat
            $table->unsignedBigInteger('id_surat');
            $table->foreign('id_surat')->references('id')->on('suratmasuk');
            // Foreign key tabel tindak lanjut
            $table->unsignedBigInteger('id_tindak_lanjut')->nullable();
            $table->foreign('id_tindak_lanjut')->references('id')->on('tindak_lanjut');

            $table->string('tujuan_disposisi')->nullable();
            $table->string('status')->nullable();
            $table->string('isi')->nullable();
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
        Schema::dropIfExists('disposisi');
    }
};
