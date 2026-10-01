@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-sm-8">
            <h1 class="m-0 fs-3 app-title">Tambah Pengajuan Solar</h1>
            <p class="text-secondary mb-0">Silakan isi formulir di bawah ini dengan lengkap.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white pt-4 pb-2 border-0">
            <h3 class="card-title fw-bold mb-0">Form Pengajuan Solar</h3>
        </div>
        
        <form action="{{ route('solar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body pt-2">
                
                {{-- TAMPILKAN PESAN ERROR JIKA MELEBIHI KAPASITAS --}}
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="tanggal_transaksi" class="form-label fw-semibold">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>

                <div class="mb-3">
                    <label for="id_penyimpanan" class="form-label fw-semibold">Sumber / Tujuan Penyimpanan</label>
                    <select name="id_penyimpanan" class="form-control form-select" required>
                        <option value="">-- Pilih Penyimpanan --</option>
                        @foreach($penyimpanan as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_penyimpanan }} ({{ $p->lokasi }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Transaksi</label>
                    <input type="text" class="form-control bg-light" value="Keluar (Pemakaian)" readonly>
                    <input type="hidden" name="jenis_transaksi" value="Keluar">
                </div>

                <div class="mb-3" id="group_penggunaan">
                    <label for="id_penggunaan" class="form-label fw-semibold">Untuk Penggunaan / Kendaraan <span class="text-danger">*</span></label>
                    <select name="id_penggunaan" class="form-control form-select" required>
                        <option value="">-- Pilih Penggunaan --</option>
                        @foreach($penggunaan as $pg)
                            <option value="{{ $pg->id }}">{{ $pg->kategori }} - {{ $pg->nama_item }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jumlah_liter" class="form-label fw-semibold">Jumlah (Liter)</label>
                    <input type="number" step="0.1" name="jumlah_liter" class="form-control" required placeholder="Contoh: 50.5">
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-semibold">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>

            <div class="mb-4 px-3">
                <label for="file_invoice" class="form-label fw-semibold">Unggah Bukti / Invoice (Opsional)</label>
                <input type="file" name="file_invoice" id="file_invoice" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted d-block mt-1">Format dokumen yang diizinkan: PDF, JPG, JPEG, PNG.</small>
            </div>

            <div class="card-footer bg-white border-top-0 pb-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary" style="background-color: #d6643c; border-color: #d6643c;">
                    Kirim Pengajuan
                </button>
                <a href="{{ route('solar.index') }}" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection