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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_disposisi');
            $table->string('keterangan');
            $table->timestamps();

            // Foreign key tabel surat
            $table->unsignedBigInteger('id_surat');
            $table->foreign('id_surat')->references('id')->on('suratmasuk');

            // Foreign key tabel tindak lanjut
            $table->unsignedBigInteger('id_tindak_lanjut');
            $table->foreign('id_tindak_lanjut')->references('id')->on('tindak_lanjut');

            // Foreign key tabel penerima
            $table->unsignedBigInteger('nip_penerima');
            $table->foreign('nip_penerima')->references('nip')->on('users');
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
