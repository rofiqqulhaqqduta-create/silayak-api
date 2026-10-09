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
    Schema::create('lokasi', function (Blueprint $table) {
        $table->id('id_lokasi');
        $table->string('nama_lokasi');
        $table->string('jenis_lokasi');
        $table->text('alamat')->nullable();
        $table->string('kontak')->nullable();
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
    Schema::dropIfExists('lokasi');
}
};
