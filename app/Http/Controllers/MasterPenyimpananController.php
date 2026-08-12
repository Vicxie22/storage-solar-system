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
            'lokasi' => 'nullable'
        ]);

        MasterPenyimpanan::create($request->all());
        return redirect()->back()->with('success', 'Data penyimpanan berhasil ditambahkan!');
    }
}