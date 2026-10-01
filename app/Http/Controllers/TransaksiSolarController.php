<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSolar;
use App\Models\MasterPenyimpanan;
use App\Models\MasterPenggunaan;
use App\Models\LogAktivitas;

class TransaksiSolarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = TransaksiSolar::with(['penyimpanan', 'penggunaan']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('tanggal_transaksi', 'like', "%{$search}%")
                  ->orWhere('jumlah_liter', 'like', "%{$search}%")
                  ->orWhere('jenis_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('penyimpanan', function($qPenyimpanan) use ($search) {
                      $qPenyimpanan->where('nama_penyimpanan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('penggunaan', function($qPenggunaan) use ($search) {
                      $qPenggunaan->where('nama_item', 'like', "%{$search}%");
                  });
            });
        }

        if (auth()->user()->role === 'Admin') {
            $transaksi = $query->latest()->get();
            return view('solar.admin_index', compact('transaksi'));
        } else {
            $transaksi = $query->where('user_id', auth()->id())->latest()->get();
            return view('solar.user_index', compact('transaksi'));
        }
    }

    public function create()
    {
        $penyimpanan = MasterPenyimpanan::all();
        $penggunaan = MasterPenggunaan::all();
        
        if (auth()->user()->role === 'Admin') {
            return view('solar.admin_create', compact('penyimpanan', 'penggunaan'));
        } else {
            return view('solar.user_create', compact('penyimpanan', 'penggunaan'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penyimpanan' => 'required',
            'jenis_transaksi' => 'required|in:Masuk,Keluar',
            'jumlah_liter' => 'required|numeric|min:0.1',
            'tanggal_transaksi' => 'required|date',
            'file_invoice' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // =========================================================
        // TAMBAHAN: VALIDASI KAPASITAS MAKSIMAL PENGGUNAAN
        // =========================================================
        if ($request->jenis_transaksi === 'Keluar' && $request->filled('id_penggunaan')) {
            $penggunaan = MasterPenggunaan::find($request->id_penggunaan);
            
            // Cek jika kapasitas_maksimal diatur (lebih dari 0)
            if ($penggunaan && $penggunaan->kapasitas_maksimal > 0) {
                if ($request->jumlah_liter > $penggunaan->kapasitas_maksimal) {
                    return redirect()->back()->withInput()->with('error', 
                        "Pengajuan Gagal: Jumlah permintaan ({$request->jumlah_liter} L) melebihi batas maksimal kapasitas untuk {$penggunaan->nama_item} ({$penggunaan->kapasitas_maksimal} L)."
                    );
                }
            }
        }
        // =========================================================

        // RESOLUSI GALAT 403: Gunakan except() untuk membuang objek file temporary Windows
        $data = $request->except('file_invoice');
        $data['user_id'] = auth()->id();

        // LOGIKA PENYIMPANAN BERKAS FISIK
        if ($request->hasFile('file_invoice')) {
            $path = $request->file('file_invoice')->store('invoices', 'public');
            $data['file_invoice'] = $path; 
        }

        if ($request->jenis_transaksi == 'Masuk') {
            $data['status'] = 'Approved'; 
            
            $tangki = MasterPenyimpanan::find($request->id_penyimpanan);
            if ($tangki) {
                $tangki->stok_sekarang += $request->jumlah_liter;
                $tangki->save();
            }
        } else {
            $data['status'] = 'Pending';
        }

        $transaksi = TransaksiSolar::create($data);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => 'Membuat Transaksi',
            'deskripsi' => 'Menginput data transaksi ' . $request->jenis_transaksi . ' solar sebanyak ' . $request->jumlah_liter . ' Liter (ID: ' . $transaksi->id . ')'
        ]);

        return redirect()->route('solar.index')->with('success', 'Data pencatatan solar berhasil disimpan.');
    }
    
    public function edit($id)
    {
        $transaksi = TransaksiSolar::findOrFail($id);
        $penyimpanan = MasterPenyimpanan::all();
        $penggunaan = MasterPenggunaan::all();
        
        if (auth()->user()->role === 'Admin') {
            return view('solar.admin_edit', compact('transaksi', 'penyimpanan', 'penggunaan'));
        } else {
            if ($transaksi->status !== 'Pending') {
                return redirect()->route('solar.index')->with('error', 'Maaf, pengajuan yang sudah diproses tidak dapat diubah.');
            }
            return view('solar.user_edit', compact('transaksi', 'penyimpanan', 'penggunaan'));
        }
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiSolar::findOrFail($id);

        if (auth()->user()->role !== 'Admin') {
            if ($transaksi->user_id !== auth()->id()) {
                abort(403, 'Akses Ditolak. Anda tidak berhak mengubah data milik orang lain.');
            }
            if ($transaksi->status !== 'Pending') {
                return redirect()->route('solar.index')->with('error', 'Maaf, data yang sudah diproses Admin tidak dapat diubah lagi.');
            }
        }

        $request->validate([
            'id_penyimpanan' => 'required',
            'jenis_transaksi' => 'required|in:Masuk,Keluar,Penyesuaian Stok',
            'jumlah_liter' => 'required|numeric|min:0.1',
            'tanggal_transaksi' => 'required|date',
            'file_invoice' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // =========================================================
        // TAMBAHAN: VALIDASI KAPASITAS MAKSIMAL SAAT EDIT
        // =========================================================
        if ($request->jenis_transaksi === 'Keluar' && $request->filled('id_penggunaan')) {
            $penggunaan = MasterPenggunaan::find($request->id_penggunaan);
            
            if ($penggunaan && $penggunaan->kapasitas_maksimal > 0) {
                if ($request->jumlah_liter > $penggunaan->kapasitas_maksimal) {
                    return redirect()->back()->withInput()->with('error', 
                        "Pembaruan Gagal: Jumlah perubahan ({$request->jumlah_liter} L) melebihi batas maksimal kapasitas untuk {$penggunaan->nama_item} ({$penggunaan->kapasitas_maksimal} L)."
                    );
                }
            }
        }
        // =========================================================

        $tangki = MasterPenyimpanan::find($transaksi->id_penyimpanan);

        $literSebelum = $transaksi->jumlah_liter;
        $literSetelah = $request->jumlah_liter;
        $selisih = $literSetelah - $literSebelum;

        if ($transaksi->status == 'Approved' && $tangki) {
            if ($transaksi->jenis_transaksi == 'Masuk' || $transaksi->jenis_transaksi == 'Penyesuaian Stok') {
                $tangki->stok_sekarang -= $transaksi->jumlah_liter;
            } elseif ($transaksi->jenis_transaksi == 'Keluar') {
                $tangki->stok_sekarang += $transaksi->jumlah_liter;
            }
            $tangki->save();
        }

        // RESOLUSI GALAT 403: Gunakan except() untuk update
        $data = $request->except('file_invoice');
        
        if ($request->jenis_transaksi == 'Masuk' || $request->jenis_transaksi == 'Penyesuaian Stok') {
            $data['status'] = 'Approved';
            if ($tangki) {
                $tangki->stok_sekarang += $request->jumlah_liter;
                $tangki->save();
            }
        } else {
            $data['status'] = 'Pending'; 
        }

        $data['informasi_edit'] = "L Sebelum Diedit: {$literSebelum} L\nL Sesudah Diedit: {$literSetelah} L\nSelisih: {$selisih} L";

        // LOGIKA PENYIMPANAN BERKAS FISIK
        if ($request->hasFile('file_invoice')) {
            $path = $request->file('file_invoice')->store('invoices', 'public');
            $data['file_invoice'] = $path;
        }

        // Eksekusi pembaruan data secara tunggal
        $transaksi->fill($data);
        $transaksi->save();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => 'Mengubah Transaksi',
            'deskripsi' => 'Mengubah jumlah liter pada transaksi ID: ' . $transaksi->id . ' dari ' . $literSebelum . ' L menjadi ' . $literSetelah . ' L'
        ]);

        return redirect()->route('solar.index')->with('success', 'Data transaksi berhasil diedit.');
    }

    public function destroy($id)
    {
        $transaksi = TransaksiSolar::findOrFail($id);
        
        if (auth()->user()->role !== 'Admin') {
            if ($transaksi->user_id !== auth()->id()) {
                abort(403, 'Akses Ditolak. Anda tidak berhak menghapus data milik orang lain.');
            }
            if ($transaksi->status !== 'Pending') {
                return redirect()->route('solar.index')->with('error', 'Maaf, data yang sudah diproses Admin tidak dapat dibatalkan/dihapus.');
            }
        }

        $infoHapus = $transaksi->jenis_transaksi . ' (' . $transaksi->jumlah_liter . ' L)';
        
        $transaksi->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => 'Menghapus Transaksi',
            'deskripsi' => 'Menghapus permanen data transaksi ID: ' . $id . ' - ' . $infoHapus
        ]);

        return redirect()->route('solar.index')->with('success', 'Data berhasil dihapus.');
    }
}