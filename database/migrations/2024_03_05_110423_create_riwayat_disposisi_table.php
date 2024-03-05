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
        Schema::create('riwayat_disposisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengirim_nip');
            $table->unsignedBigInteger('penerima_nip');
            $table->unsignedBigInteger('id_surat');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('pengirim_nip')->references('nip')->on('users');
            $table->foreign('penerima_nip')->references('nip')->on('users');
            $table->foreign('id_surat')->references('id')->on('suratMasuk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_disposisi');
    }
};
