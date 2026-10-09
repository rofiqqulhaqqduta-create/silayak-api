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
    Schema::create('notifikasi', function (Blueprint $table) {
        $table->id('id_notifikasi');

        $table->unsignedBigInteger('id_laporan')->nullable();
        $table->unsignedBigInteger('id_user_tujuan');

        $table->text('pesan');

        $table->enum('status_baca', [
            'Belum Dibaca',
            'Sudah Dibaca'
        ])->default('Belum Dibaca');

        $table->timestamps();

        $table->foreign('id_laporan')
            ->references('id_laporan')
            ->on('laporan')
            ->onDelete('cascade');

        $table->foreign('id_user_tujuan')
            ->references('id')
            ->on('petugas')
            ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down()
{
    Schema::dropIfExists('notifikasi');
}
};
