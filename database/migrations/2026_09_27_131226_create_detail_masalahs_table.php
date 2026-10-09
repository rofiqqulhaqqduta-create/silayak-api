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
        Schema::create('detail_masalah', function (Blueprint $table) {
    $table->id('id_detail');

    $table->unsignedBigInteger('id_laporan');
    $table->unsignedBigInteger('id_kategori');

    $table->foreign('id_laporan')
        ->references('id_laporan')
        ->on('laporan')
        ->onDelete('cascade');

    $table->foreign('id_kategori')
        ->references('id_kategori')
        ->on('kategori_masalah')
        ->onDelete('restrict');

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
        Schema::dropIfExists('detail_masalahs');
    }
};
