<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('suratMasuk', function (Blueprint $table) {
            $table->id();
            $table->integer('nomorAgenda')->unique();
            $table->string('nomorSurat')->unique();
            $table->string('kodeHal');
            $table->string('sifatSurat');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kodeHal')->references('kode')->on('hal')->onDelete('cascade');
            $table->foreign('sifatSurat')->references('kode')->on('sifat')->onDelete('cascade');
            $table->date('tanggalPengajuan');
            $table->string('asalSurat');
            $table->string('tujuanSurat');
            $table->integer('jumlahLampiran')->nullable();
            $table->string('lampiran');
            $table->longText('perihal')->nullable();
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
        Schema::dropIfExists('suratMasuk');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
