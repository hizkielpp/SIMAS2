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
        Schema::create('suratKeluar', function (Blueprint $table) {
            $table->id();
            $table->integer('nomorAgenda')->nullable();
            $table->integer('nomorSurat');
            $table->string('kodeHal');
            $table->string('sifatSurat');
            $table->unsignedBigInteger('created_by');
            $table->foreign('kodeHal')->references('kode')->on('hal')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sifatSurat')->references('kode')->on('sifat')->onDelete('cascade');
            $table->string('lampiran');
            $table->date('tanggalPengesahan');
            // $table->string('asalSurat');
            $table->string('kodeUnit');
            $table->string('disahkanOleh');
            $table->enum('jenis', ['biasa', 'antidatir']);
            $table->enum('status',['digunakan','belum'])->default('belum');
            $table->string('tujuanSurat');
            $table->integer('jumlahLampiran');
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
        Schema::dropIfExists('suratKeluar');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
