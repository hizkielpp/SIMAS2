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
        Schema::create('tembusan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Foreign key tabel surat masuk
            $table->unsignedBigInteger('id_surat');
            $table->foreign('id_surat')->references('id')->on('suratMasuk')->onDelete('cascade');

            // Foreign key tabel users
            $table->unsignedBigInteger('nip_tembusan');
            $table->foreign('nip_tembusan')->references('nip')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tembusan');
    }
};
