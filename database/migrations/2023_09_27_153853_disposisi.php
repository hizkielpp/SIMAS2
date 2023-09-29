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
            $table->string('surat_id');
            $table->foreign('surat_id')->references('nomorSurat')->on('suratmasuk');
            $table->string('tujuan_disposisi');
            $table->foreign('created_by');
            $table->string('tindak_lanjut');
            $table->string('catatan');
            $table->string('status');
            $table->string('tanggal_disposisi');
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
