<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterPenggunaan;

class MasterPenggunaanController extends Controller
{
    public function index()
    {
        $penggunaan = MasterPenggunaan::latest()->get();
        return view('penggunaan.index', compact('penggunaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required',
            'nama_item' => 'required'
        ]);

        MasterPenggunaan::create($request->all());
        return redirect()->back()->with('success', 'Item penggunaan berhasil ditambahkan!');
    }
}