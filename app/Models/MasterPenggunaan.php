<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPenggunaan extends Model
{
    protected $table = 'master_penggunaans';
    protected $fillable = ['kategori', 'nama_item'];

    public function transaksi()
    {
        return $this->hasMany(TransaksiSolar::class, 'id_penggunaan');
    }
}