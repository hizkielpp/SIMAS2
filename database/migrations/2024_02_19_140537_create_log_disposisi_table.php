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
        Schema::create('log_disposisi', function (Blueprint $table) {
            $table->id();
            // Foreign key tabel surat
            $table->unsignedBigInteger('id_disposisi');
            $table->foreign('id_disposisi')->references('id')->on('disposisi');
            $table->string('status');
            $table->string('isi');

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
        Schema::dropIfExists('log_disposisi');
    }
};
