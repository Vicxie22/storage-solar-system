<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSolar;
use App\Models\LogAktivitas; // <-- WAJIB DITAMBAHKAN

class ApprovalSolarController extends Controller
{
    // Menampilkan daftar transaksi yang butuh persetujuan
    public function index()
    {
        // Ambil hanya transaksi 'Keluar' yang masih 'Pending'
        $transaksi = TransaksiSolar::with(['penyimpanan', 'penggunaan'])
                    ->where('jenis_transaksi', 'Keluar')
                    ->where('status', 'Pending')
                    ->latest()
                    ->get();
                    
        return view('solar.approval', compact('transaksi'));
    }

    // Fungsi untuk menyetujui atau menolak
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected'
        ]);

        $transaksi = TransaksiSolar::findOrFail($id);
        
        if ($request->status == 'Approved') {
            $tangki = \App\Models\MasterPenyimpanan::find($transaksi->id_penyimpanan);
            
            // CEK VALIDASI: Apakah stok yang diminta lebih besar dari stok fisik?
            if ($tangki->stok_sekarang < $transaksi->jumlah_liter) {
                return back()->with('error', 'Gagal disetujui! Stok di ' . $tangki->nama_penyimpanan . ' tidak mencukupi. (Sisa saat ini: ' . $tangki->stok_sekarang . ' Liter)');
            }

            // Jika stok cukup, baru kurangi
            $tangki->stok_sekarang -= $transaksi->jumlah_liter;
            $tangki->save();
        }

        $transaksi->status = $request->status;
        $transaksi->save();

        // ---------------------------------------------------------
        // CATAT KE LOG AKTIVITAS (MENYETUJUI / MENOLAK PENGAJUAN)
        // ---------------------------------------------------------
        $namaAksi = $request->status == 'Approved' ? 'Menyetujui Pengajuan' : 'Menolak Pengajuan';
        
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => 'Approval Transaksi',
            'deskripsi' => $namaAksi . ' solar untuk ID: ' . $id . ' sebanyak ' . $transaksi->jumlah_liter . ' Liter.'
        ]);

        $pesan = $request->status == 'Approved' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('solar.approval')->with('success', "Transaksi berhasil $pesan.");
    }
}