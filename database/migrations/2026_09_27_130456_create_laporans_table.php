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
       Schema::create('laporan', function (Blueprint $table) {
    $table->id('id_laporan');

    $table->unsignedBigInteger('id_user');
    $table->unsignedBigInteger('id_menu');

    $table->dateTime('tanggal_lapor')->useCurrent();

    $table->enum('status_kelayakan', [
        'Layak',
        'Tidak Layak'
    ]);

    $table->string('foto_bukti')->nullable();
    $table->text('catatan')->nullable();

    $table->enum('status_verifikasi', [
        'Menunggu',
        'Disetujui',
        'Ditolak'
    ])->default('Menunggu');

    $table->foreign('id_user')
        ->references('id')
        ->on('petugas')
        ->onDelete('restrict');

    $table->foreign('id_menu')
        ->references('id_menu')
        ->on('menu')
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
        Schema::dropIfExists('laporans');
    }
};
