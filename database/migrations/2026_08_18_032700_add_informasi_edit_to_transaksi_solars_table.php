<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('transaksi_solars', function (Blueprint $table) {
            // Menambahkan kolom informasi_edit tepat setelah kolom keterangan
            $table->text('informasi_edit')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_solars', function (Blueprint $table) {
            $table->dropColumn('informasi_edit');
        });
    }
};
