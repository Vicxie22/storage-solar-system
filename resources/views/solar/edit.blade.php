@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Edit Catatan Solar</h1>
        <p class="text-secondary mb-0">Perbarui data transaksi yang sudah dicatat sebelumnya.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('solar.update', $transaksi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Transaksi</label>
                <input type="date" name="tanggal_transaksi" class="form-control" value="{{ date('Y-m-d', strtotime($transaksi->tanggal_transaksi)) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Lokasi Penyimpanan</label>
                <select name="id_penyimpanan" class="form-select" required>
                    @foreach($penyimpanan as $tangki)
                        <option value="{{ $tangki->id }}" {{ $transaksi->id_penyimpanan == $tangki->id ? 'selected' : '' }}>
                            {{ $tangki->nama_penyimpanan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jenis Transaksi</label>
                <select name="jenis_transaksi" class="form-select" required>
                    <option value="Masuk" {{ $transaksi->jenis_transaksi == 'Masuk' ? 'selected' : '' }}>Masuk (Pengisian ke Tangki)</option>
                    <option value="Keluar" {{ $transaksi->jenis_transaksi == 'Keluar' ? 'selected' : '' }}>Keluar (Penggunaan Lapangan)</option>
                    <option value="Penyesuaian Stok" {{ $transaksi->jenis_transaksi == 'Penyesuaian Stok' ? 'selected' : '' }}>Penyesuaian Stok (Edit Manual)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Item Penggunaan (Kosongkan jika Masuk)</label>
                <select name="id_penggunaan" class="form-select">
                    <option value="">-- Pilih Penggunaan --</option>
                    @foreach($penggunaan as $guna)
                        <option value="{{ $guna->id }}" {{ $transaksi->id_penggunaan == $guna->id ? 'selected' : '' }}>
                            {{ $guna->nama_item }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah (Liter)</label>
                <input type="number" step="0.1" name="jumlah_liter" class="form-control" value="{{ $transaksi->jumlah_liter }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ $transaksi->keterangan }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('solar.index') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary" style="background-color: #d6643c; border-color: #d6643c;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection