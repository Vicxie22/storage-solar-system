<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSolar;

class RiwayatSolarController extends Controller
{
    public function index()
    {
        // Hanya mengambil transaksi yang sudah "Approved" (Selesai diproses)
        $history = TransaksiSolar::with(['penyimpanan', 'penggunaan'])
                    ->where('status', 'Approved')
                    ->latest()
                    ->paginate(15);
                    
        return view('solar.history', compact('history'));
    }
}