<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSolar;
use App\Models\MasterPenyimpanan;
use App\Models\MasterPenggunaan;
use App\Models\LogAktivitas; // <-- WAJIB DITAMBAHKAN

class TransaksiSolarController extends Controller
{
    // Pastikan parameter Request $request ditambahkan di dalam kurung
    public function index(Request $request)
    {
        // 1. Tangkap kata kunci pencarian dari kolom input
        $search = $request->input('search');

        // 2. Siapkan query dasar beserta relasinya
        $query = TransaksiSolar::with(['penyimpanan', 'penggunaan']);

        // 3. JIKA ADA PENCARIAN: Terapkan logika pencarian "Sapu Jagat" (Universal)
        if ($search) {
            // Kita bungkus dalam function($q) agar logika OR tidak bertabrakan dengan filter User ID
            $query->where(function($q) use ($search) {
                $q->where('tanggal_transaksi', 'like', "%{$search}%")
                  ->orWhere('jumlah_liter', 'like', "%{$search}%")
                  ->orWhere('jenis_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  
                  // Cari ke dalam tabel relasi Master Penyimpanan
                  ->orWhereHas('penyimpanan', function($qPenyimpanan) use ($search) {
                      $qPenyimpanan->where('nama_penyimpanan', 'like', "%{$search}%");
                  })
                  
                  // Cari ke dalam tabel relasi Master Penggunaan
                  ->orWhereHas('penggunaan', function($qPenggunaan) use ($search) {
                      $qPenggunaan->where('nama_item', 'like', "%{$search}%");
                  });
            });
        }

        // 4. PISAHKAN JALUR ADMIN & USER
        if (auth()->user()->role === 'Admin') {
            $transaksi = $query->latest()->get();
            return view('solar.admin_index', compact('transaksi'));
        } else {
            // Tambahkan filter where user_id SETELAH logika pencarian, agar aman
            $transaksi = $query->where('user_id', auth()->id())->latest()->get();
            return view('solar.user_index', compact('transaksi'));
        }
    }

    public function create()
    {
        // Mengambil data master untuk ditampilkan di dropdown form
        $penyimpanan = MasterPenyimpanan::all();
        $penggunaan = MasterPenggunaan::all();
        
        // JALUR KHUSUS ADMIN
        if (auth()->user()->role === 'Admin') {
            return view('solar.admin_create', compact('penyimpanan', 'penggunaan'));
        } 
        // JALUR KHUSUS USER
        else {
            return view('solar.user_create', compact('penyimpanan', 'penggunaan'));
        }
    }

    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'id_penyimpanan' => 'required',
            'jenis_transaksi' => 'required|in:Masuk,Keluar',
            'jumlah_liter' => 'required|numeric|min:0.1',
            'tanggal_transaksi' => 'required|date',
            'file_invoice' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $request->merge([
            'user_id' => auth()->id()
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
        $transaksi = TransaksiSolar::create($data);

        // ---------------------------------------------------------
        // CATAT KE LOG AKTIVITAS (MEMBUAT PENGAJUAN)
        // ---------------------------------------------------------
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
        
        // JALUR KHUSUS ADMIN
        if (auth()->user()->role === 'Admin') {
            return view('solar.admin_edit', compact('transaksi', 'penyimpanan', 'penggunaan'));
        } 
        // JALUR KHUSUS USER
        else {
            // KEAMANAN: Pastikan User hanya bisa edit jika statusnya masih Pending
            if ($transaksi->status !== 'Pending') {
                return redirect()->route('solar.index')->with('error', 'Maaf, pengajuan yang sudah diproses tidak dapat diubah.');
            }
            return view('solar.user_edit', compact('transaksi', 'penyimpanan', 'penggunaan'));
        }
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiSolar::findOrFail($id);

        // =========================================================
        // GEMBOK KEAMANAN KHUSUS USER BIASA
        // =========================================================
        if (auth()->user()->role !== 'Admin') {
            // 1. Tolak jika transaksi ini milik orang lain
            if ($transaksi->user_id !== auth()->id()) {
                abort(403, 'Akses Ditolak. Anda tidak berhak mengubah data milik orang lain.');
            }
            // 2. Tolak jika statusnya sudah disetujui/ditolak (bukan Pending)
            if ($transaksi->status !== 'Pending') {
                return redirect()->route('solar.index')->with('error', 'Maaf, data yang sudah diproses Admin tidak dapat diubah lagi.');
            }
        }
        // =========================================================

        $request->validate([
            'id_penyimpanan' => 'required',
            'jenis_transaksi' => 'required|in:Masuk,Keluar,Penyesuaian Stok',
            'jumlah_liter' => 'required|numeric|min:0.1',
            'tanggal_transaksi' => 'required|date',
            'file_invoice' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $tangki = MasterPenyimpanan::find($transaksi->id_penyimpanan);

        // --- 1. TANGKAP LITER TRANSAKSI (SEBELUM & SESUDAH) ---
        $literSebelum = $transaksi->jumlah_liter;
        $literSetelah = $request->jumlah_liter;
        $selisih = $literSetelah - $literSebelum;

        // --- 2. REVERT STOK LAMA DARI TANGKI ---
        if ($transaksi->status == 'Approved' && $tangki) {
            if ($transaksi->jenis_transaksi == 'Masuk' || $transaksi->jenis_transaksi == 'Penyesuaian Stok') {
                $tangki->stok_sekarang -= $transaksi->jumlah_liter;
            } elseif ($transaksi->jenis_transaksi == 'Keluar') {
                $tangki->stok_sekarang += $transaksi->jumlah_liter;
            }
            $tangki->save();
        }

        // --- 3. UPDATE DATA TRANSAKSI & APPLY STOK BARU KE TANGKI ---
        $transaksi->fill($request->all());
        
        if ($request->jenis_transaksi == 'Masuk' || $request->jenis_transaksi == 'Penyesuaian Stok') {
            $transaksi->status = 'Approved';
            if ($tangki) {
                $tangki->stok_sekarang += $transaksi->jumlah_liter;
                $tangki->save();
            }
        } else {
            // Jika diubah menjadi Keluar, kembalikan ke status Pending
            $transaksi->status = 'Pending'; 
        }

        // --- 4. FORMAT KETERANGAN BARU ---
        $transaksi->informasi_edit = "L Sebelum Diedit: {$literSebelum} L\nL Sesudah Diedit: {$literSetelah} L\nSelisih: {$selisih} L";
        $data = $request->all();

            if ($request->hasFile('file_invoice')) {
            // Simpan berkas ke direktori storage/app/public/invoices
            $path = $request->file('file_invoice')->store('invoices', 'public');
            $data['file_invoice'] = $path;
        }

        $transaksi->fill($data);
        $transaksi->save();

        // --- 5. CATAT KE LOG AKTIVITAS ---
        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => 'Mengubah Transaksi',
            'deskripsi' => 'Mengubah jumlah liter pada transaksi ID: ' . $transaksi->id . ' dari ' . $literSebelum . ' L menjadi ' . $literSetelah . ' L'
        ]);

        return redirect()->route('solar.index')->with('success', 'Data transaksi berhasil diedit.');
    }

    // Method untuk menghapus data
    public function destroy($id)
    {
        $transaksi = TransaksiSolar::findOrFail($id);
        
        // =========================================================
        // GEMBOK KEAMANAN KHUSUS USER BIASA
        // =========================================================
        if (auth()->user()->role !== 'Admin') {
            // 1. Tolak jika transaksi ini milik orang lain
            if ($transaksi->user_id !== auth()->id()) {
                abort(403, 'Akses Ditolak. Anda tidak berhak menghapus data milik orang lain.');
            }
            // 2. Tolak jika statusnya sudah disetujui/ditolak (bukan Pending)
            if ($transaksi->status !== 'Pending') {
                return redirect()->route('solar.index')->with('error', 'Maaf, data yang sudah diproses Admin tidak dapat dibatalkan/dihapus.');
            }
        }
        // =========================================================

        // Simpan info sementara sebelum dihapus untuk dimasukkan ke deskripsi log
        $infoHapus = $transaksi->jenis_transaksi . ' (' . $transaksi->jumlah_liter . ' L)';
        
        $transaksi->delete();

        // ---------------------------------------------------------
        // CATAT KE LOG AKTIVITAS (MENGHAPUS PENGAJUAN)
        // ---------------------------------------------------------
        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => 'Menghapus Transaksi',
            'deskripsi' => 'Menghapus permanen data transaksi ID: ' . $id . ' - ' . $infoHapus
        ]);

        return redirect()->route('solar.index')->with('success', 'Data berhasil dihapus.');
    }
}