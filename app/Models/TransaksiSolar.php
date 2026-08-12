<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiSolar extends Model
{
    protected $table = 'transaksi_solars';
    
    protected $fillable = [
        'user_id',
        'id_penyimpanan',
        'jenis_transaksi',
        'id_penggunaan',
        'jumlah_liter',
        'tanggal_transaksi',
        'keterangan',
        'status'
    ];

    // Relasi balik ke tabel master penyimpanan
    public function penyimpanan()
    {
        return $this->belongsTo(MasterPenyimpanan::class, 'id_penyimpanan');
    }

    // Relasi balik ke tabel master penggunaan
    public function penggunaan()
    {
        return $this->belongsTo(MasterPenggunaan::class, 'id_penggunaan');
    }
}