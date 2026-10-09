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
    Schema::create('petugas', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->string('role')->default('Petugas');
        $table->unsignedBigInteger('id_lokasi')->nullable();
        $table->string('status')->default('Menunggu');
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
        Schema::dropIfExists('petugas');
    }
};
