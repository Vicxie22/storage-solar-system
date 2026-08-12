@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <!-- 1. Judul Halaman Dinamis -->
            <h1 class="m-0">{{ auth()->user()->role === 'Admin' ? 'Tambah Catatan Solar' : 'Tambah Pengajuan Solar' }}</h1>
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <!-- 2. Judul Form Dinamis -->
            <h3 class="card-title">{{ auth()->user()->role === 'Admin' ? 'Form Pencatatan Solar' : 'Form Pengajuan Solar' }}</h3>
        </div>
        
        <form action="{{ route('solar.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="tanggal_transaksi">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="id_penyimpanan">Sumber / Tujuan Penyimpanan</label>
                    <select name="id_penyimpanan" class="form-control" required>
                        <option value="">-- Pilih Penyimpanan --</option>
                        @foreach($penyimpanan as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_penyimpanan }} ({{ $p->lokasi }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label fw-semibold">Jenis Transaksi</label>
                    
                    @if(auth()->user()->role === 'Admin')
                        <select name="jenis_transaksi" class="form-control" required>
                            <option value="Keluar">Keluar (Pemakaian)</option>
                            <option value="Masuk">Masuk (Pengisian ke Tangki)</option>
                        </select>
                    @else
                        <!-- Jika User biasa, kunci otomatis ke Keluar -->
                        <input type="text" class="form-control bg-light" value="Keluar (Pemakaian)" readonly>
                        <input type="hidden" name="jenis_transaksi" value="Keluar">
                    @endif
                </div>

                <div class="form-group" id="group_penggunaan">
                    <!-- 3. Label Penggunaan Dinamis -->
                    <label for="id_penggunaan">{{ auth()->user()->role === 'Admin' ? 'Untuk Penggunaan (Kosongkan jika transaksi masuk)' : 'Untuk Penggunaan / Kendaraan' }}</label>
                    
                    <!-- 4. Wajib diisi (required) untuk User biasa -->
                    <select name="id_penggunaan" class="form-control" {{ auth()->user()->role === 'Admin' ? '' : 'required' }}>
                        <option value="">-- Pilih Penggunaan --</option>
                        @foreach($penggunaan as $pg)
                            <option value="{{ $pg->id }}">{{ $pg->kategori }} - {{ $pg->nama_item }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah_liter">Jumlah (Liter)</label>
                    <input type="number" step="0.1" name="jumlah_liter" class="form-control" required placeholder="Contoh: 50.5">
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>

            <div class="card-footer">
                <!-- 5. Tombol Submit Dinamis -->
                <button type="submit" class="btn btn-primary">{{ auth()->user()->role === 'Admin' ? 'Simpan Data' : 'Kirim Pengajuan' }}</button>
                <a href="{{ route('solar.index') }}" class="btn btn-default float-right">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection