<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterPenyimpanan;

class MasterPenyimpananController extends Controller
{
    public function index()
    {
        $penyimpanan = MasterPenyimpanan::latest()->get();
        return view('penyimpanan.index', compact('penyimpanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penyimpanan' => 'required',
            'lokasi' => 'nullable',
            'kapasitas_maksimal' => 'required'
        ]);

        MasterPenyimpanan::create($request->all());
        return redirect()->back()->with('success', 'Data penyimpanan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
    try {
        $penyimpanan = \App\Models\MasterPenyimpanan::findOrFail($id);
        $penyimpanan->delete();
        
        return redirect()->route('penyimpanan.index')->with('success', 'Data lokasi penyimpanan berhasil dihapus secara permanen.');
    } catch (\Illuminate\Database\QueryException $e) {
        // Penanganan galat integritas relasi basis data
        return redirect()->route('penyimpanan.index')->withErrors(['Kegagalan sistem: Data tidak dapat dihapus karena masih memiliki relasi dengan riwayat pencatatan transaksi solar.']);
    }
    }
}