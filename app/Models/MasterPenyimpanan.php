<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPenyimpanan extends Model
{
    protected $table = 'master_penyimpanans';
    protected $fillable = ['nama_penyimpanan', 'lokasi', 'kapasitas_maksimal'];

    public function transaksi()
    {
        return $this->hasMany(TransaksiSolar::class, 'id_penyimpanan');
    }
}