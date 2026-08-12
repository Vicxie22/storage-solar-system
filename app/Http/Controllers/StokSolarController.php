<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterPenyimpanan;

class StokSolarController extends Controller
{
    public function index()
    {
        $penyimpanan = MasterPenyimpanan::all();
        return view('solar.stok', compact('penyimpanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['stok_sekarang' => 'required|numeric|min:0']);
        
        $tangki = MasterPenyimpanan::findOrFail($id);
        
        $stokLama = $tangki->stok_sekarang;
        $stokBaru = $request->stok_sekarang;
        $selisih = $stokBaru - $stokLama;

        // Jika tidak ada perubahan angka, langsung kembalikan saja
        if ($selisih == 0) {
            return back()->with('success', 'Tidak ada perubahan stok.');
        }

        // 1. Update Stok di Master Penyimpanan
        $tangki->stok_sekarang = $stokBaru;
        $tangki->save();

        // 2. Catat otomatis ke Riwayat (TransaksiSolar)
        \App\Models\TransaksiSolar::create([
            'id_penyimpanan' => $tangki->id,
            'jenis_transaksi' => 'Penyesuaian Stok',
            'jumlah_liter' => $stokBaru, // Selisih absolut untuk jumlah liter
            'tanggal_transaksi' => now(),
            // Kita simpan detail perubahannya di dalam keterangan
            'keterangan' => "Stok Awal: {$stokLama} L\nStok Akhir: {$stokBaru} L\nSelisih: {$selisih} L",
            'status' => 'Approved'
        ]);

        return back()->with('success', 'Stok tangki berhasil diperbarui dan telah dicatat di Riwayat.');
    }
}