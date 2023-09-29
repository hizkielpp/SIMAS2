<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->string('surat_masuk_id');
            $table->foreign('surat_masuk_id')->references('nomorSurat')->on('suratmasuk');
            $table->string('pengirim_disposisi');
            // $table->foreign('pengirim_disposisi')->references('teruskan_ke')->on('suratmasuk');
            $table->string('penerima_disposisi');
            $table->string('status');
            $table->string('catatan_disposisi');
            $table->dateTime('tanggal_disposisi');
            $table->string('tindak_lanjut');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('disposisi');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        //
    }
};
