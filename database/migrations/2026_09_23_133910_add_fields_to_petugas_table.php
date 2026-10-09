<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->string('username')->unique()->after('nama');
            $table->string('role')->default('Petugas')->after('password');
            $table->unsignedBigInteger('id_lokasi')->nullable()->after('role');
        });
    }

    public function down()
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'id_lokasi']);
        });
    }
};