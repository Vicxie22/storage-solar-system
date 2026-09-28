<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('master_penyimpanans', function (Blueprint $table) {
        $table->decimal('kapasitas_maksimal', 10, 2)->default(0)->after('stok_sekarang');
    });

    Schema::table('master_penggunaans', function (Blueprint $table) {
        $table->decimal('kapasitas_maksimal', 10, 2)->default(0)->after('nama_item');
    });

    Schema::table('transaksi_solars', function (Blueprint $table) {
        $table->string('file_invoice')->nullable()->after('keterangan');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solar_tables', function (Blueprint $table) {
            //
        });
    }
};
