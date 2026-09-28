<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
    Schema::table('master_penyimpanans', function (Blueprint $table) {
        $table->double('stok_sekarang')->default(0)->after('lokasi');
    });
    }

    
    public function down(): void
    {
    Schema::table('master_penyimpanans', function (Blueprint $table) {
        $table->dropColumn('stok_sekarang');
    });
    }
};
