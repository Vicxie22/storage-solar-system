<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_solars', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel master_penyimpanans (Dari mana solar diambil/disimpan)
            $table->foreignId('id_penyimpanan')->constrained('master_penyimpanans')->onDelete('cascade');
            
            // Jenis transaksi (Apakah stok bertambah atau berkurang)
            $table->enum('jenis_transaksi', ['Masuk', 'Keluar', 'Penyesuaian Stok']);
            
            // Relasi ke tabel master_penggunaans (Bisa kosong/null jika transaksi Masuk)
            $table->foreignId('id_penggunaan')->nullable()->constrained('master_penggunaans')->onDelete('cascade');
            
            // Jumlah solar dalam liter
            $table->decimal('jumlah_liter', 8, 2);
            
            // Tanggal pencatatan
            $table->date('tanggal_transaksi');
            
            // Catatan tambahan jika diperlukan
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_solars');
    }
};