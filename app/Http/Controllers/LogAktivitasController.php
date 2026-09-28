<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas; // Ubah pemanggilan modelnya

class LogAktivitasController extends Controller
{
    public function index()
    {
        // Mengambil log aktivitas dari yang terbaru
        $logs = LogAktivitas::with('user')->latest()->paginate(20);
                    
        // Arahkan ke file view yang baru
        return view('solar.log_aktivitas', compact('logs'));
    }
}