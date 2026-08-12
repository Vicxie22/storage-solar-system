<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSolar;
use App\Models\MasterPenyimpanan;
use App\Models\MasterPenggunaan;

class TransaksiSolarController extends Controller
{
    public function index()
    {
        // Mengambil semua data transaksi beserta relasinya
        $transaksi = TransaksiSolar::with(['penyimpanan', 'penggunaan'])->latest()->get();
        return view('solar.index', compact('transaksi'));
    }

    public function create()
    {
        // Mengambil data master untuk ditampilkan di dropdown form
        $penyimpanan = MasterPenyimpanan::all();
        $penggunaan = MasterPenggunaan::all();
        
        return view('solar.create', compact('penyimpanan', 'penggunaan'));
    }

    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'id_penyimpanan' => 'required',
            'jenis_transaksi' => 'required|in:Masuk,Keluar',
            'jumlah_liter' => 'required|numeric|min:0.1',
            'tanggal_transaksi' => 'required|date',
        ]);

        $data = $request->all();

        // LOGIKA OTOMATISASI STOK DAN STATUS
        if ($request->jenis_transaksi == 'Masuk') {
            $data['status'] = 'Approved'; // Transaksi masuk langsung disetujui
            
            // Tambah Stok secara otomatis
            $tangki = MasterPenyimpanan::find($request->id_penyimpanan);
            if ($tangki) {
                $tangki->stok_sekarang += $request->jumlah_liter;
                $tangki->save();
            }
        } else {
            $data['status'] = 'Pending'; // Transaksi keluar butuh approval
        }

        // Simpan data ke database
        TransaksiSolar::create($data);

        return redirect()->route('solar.index')->with('success', 'Data pencatatan solar berhasil disimpan.');
    }
    
    public function edit($id)
    {
        $transaksi = TransaksiSolar::findOrFail($id);
        $penyimpanan = MasterPenyimpanan::all();
        $penggunaan = MasterPenggunaan::all();
        
        return view('solar.edit', compact('transaksi', 'penyimpanan', 'penggunaan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_penyimpanan' => 'required',
            'jenis_transaksi' => 'required|in:Masuk,Keluar,Penyesuaian Stok',
            'jumlah_liter' => 'required|numeric|min:0.1',
            'tanggal_transaksi' => 'required|date',
        ]);

        $transaksi = TransaksiSolar::findOrFail($id);
        $tangkiLama = MasterPenyimpanan::find($transaksi->id_penyimpanan);

        // 1. REVERT STOK: Tarik kembali stok dari transaksi lama
        if ($transaksi->status == 'Approved' && $tangkiLama) {
            if ($transaksi->jenis_transaksi == 'Masuk' || $transaksi->jenis_transaksi == 'Penyesuaian Stok') {
                $tangkiLama->stok_sekarang -= $transaksi->jumlah_liter;
            } elseif ($transaksi->jenis_transaksi == 'Keluar') {
                $tangkiLama->stok_sekarang += $transaksi->jumlah_liter;
            }
            $tangkiLama->save();
        }

        // 2. UPDATE DATA TRANSAKSI
        $transaksi->fill($request->all());
        
        if ($request->jenis_transaksi == 'Masuk' || $request->jenis_transaksi == 'Penyesuaian Stok') {
            $transaksi->status = 'Approved';
        } else {
            // Jika diubah menjadi Keluar, kembalikan ke status Pending agar melalui Approval
            $transaksi->status = 'Pending'; 
        }
        $transaksi->save();

        // 3. APPLY STOK BARU: Terapkan angka baru ke tangki
        $tangkiBaru = MasterPenyimpanan::find($transaksi->id_penyimpanan);
        if ($transaksi->status == 'Approved' && $tangkiBaru) {
            if ($transaksi->jenis_transaksi == 'Masuk' || $transaksi->jenis_transaksi == 'Penyesuaian Stok') {
                $tangkiBaru->stok_sekarang += $transaksi->jumlah_liter;
            }
            $tangkiBaru->save();
        }

        return redirect()->route('solar.index')->with('success', 'Data pencatatan solar berhasil diperbarui.');
    }

    // (Opsional) Method untuk menghapus data jika diperlukan
    public function destroy($id)
    {
        $transaksi = TransaksiSolar::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('solar.index')->with('success', 'Data berhasil dihapus.');
    }
}