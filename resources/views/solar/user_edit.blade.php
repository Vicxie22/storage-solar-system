@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Edit Pengajuan Solar</h1>
        <p class="text-secondary mb-0">Perbarui data pengajuan Anda (hanya berlaku jika status belum diproses).</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('solar.update', $transaksi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_transaksi" class="form-control" value="{{ date('Y-m-d', strtotime($transaksi->tanggal_transaksi)) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Sumber Tangki Pengambilan</label>
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
                <!-- Terkunci otomatis ke Keluar -->
                <input type="text" class="form-control bg-light" value="Keluar (Pemakaian)" readonly>
                <input type="hidden" name="jenis_transaksi" value="Keluar">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Untuk Penggunaan / Kendaraan <span class="text-danger">*</span></label>
                <!-- Wajib diisi oleh user -->
                <select name="id_penggunaan" class="form-select" required>
                    <option value="">-- Pilih Penggunaan --</option>
                    @foreach($penggunaan as $guna)
                        <option value="{{ $guna->id }}" {{ $transaksi->id_penggunaan == $guna->id ? 'selected' : '' }}>
                            {{ $guna->nama_item }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah yang Diajukan (Liter)</label>
                <input type="number" step="0.1" name="jumlah_liter" class="form-control" value="{{ $transaksi->jumlah_liter }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ $transaksi->keterangan }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" style="background-color: #d6643c; border-color: #d6643c;">Simpan Perubahan</button>
                <a href="{{ route('solar.index') }}" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection