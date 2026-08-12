<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSolar;
use Illuminate\Http\Request;

class RiwayatSolarController extends Controller
{
    public function index()
    {
        // 1. Buat kerangka dasar pencarian tanpa filter status apa pun dulu
        $query = TransaksiSolar::with(['penyimpanan', 'penggunaan']);

        // 2. Filter berdasarkan Role
        if (auth()->user()->role === 'Admin') {
            // Admin: Hanya melihat data yang sudah Selesai diproses (Disetujui & Ditolak)
            $query->whereIn('status', ['Approved', 'Rejected']);
        } else {
            // User: Melihat SEMUA pengajuan miliknya sendiri (termasuk yang masih Pending / Menunggu Admin)
            $query->where('user_id', auth()->id());
        }

        // 3. Eksekusi query dengan pengurutan terbaru dan pagination
        $history = $query->latest('tanggal_transaksi')->paginate(15);
                    
        return view('solar.history', compact('history'));
    }
}