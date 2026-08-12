<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_penggunaans', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // Misalnya: Genset, Mobil Dinas, dll
            $table->string('nama_item'); // Misalnya: Genset A, Mobil BK 1234 XX
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_penggunaans');
    }
};