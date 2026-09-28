@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-sm-8">
            <h1 class="m-0 fs-3 app-title">Tambah Catatan Solar</h1>
            <p class="text-secondary mb-0">Silakan isi formulir di bawah ini dengan lengkap.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white pt-4 pb-2 border-0">
            <h3 class="card-title fw-bold mb-0">Form Pencatatan Solar</h3>
        </div>
        
        <!-- PENAMBAHAN ENCTYPE UNTUK MENANGANI BERKAS -->
        <form action="{{ route('solar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body pt-2">
                
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
                    <select name="jenis_transaksi" id="jenis_transaksi" class="form-control form-select" required>
                        <option value="Keluar">Keluar (Pemakaian)</option>
                        <option value="Masuk">Masuk (Pengisian ke Tangki)</option>
                    </select>
                </div>

                <div class="mb-3" id="group_penggunaan">
                    <label for="id_penggunaan" class="form-label fw-semibold">Untuk Penggunaan (Kosongkan jika transaksi masuk)</label>
                    <select name="id_penggunaan" id="id_penggunaan" class="form-control form-select">
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

                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-semibold">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>

                <!-- PENAMBAHAN ELEMEN INPUT UNGGAH DOKUMEN -->
                <div class="mb-4">
                    <label for="file_invoice" class="form-label fw-semibold">Unggah Faktur / Invoice (Opsional)</label>
                    <input type="file" name="file_invoice" id="file_invoice" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>

            <div class="card-footer bg-white border-top-0 pb-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary" style="background-color: #d6643c; border-color: #d6643c;">
                    Simpan Data
                </button>
                <a href="{{ route('solar.index') }}" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT OTOMATISASI PENONAKTIFAN DROPDOWN PENGGUNAAN -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisTransaksi = document.getElementById('jenis_transaksi');
        const penggunaan = document.getElementById('id_penggunaan');

        function togglePenggunaan() {
            if (jenisTransaksi && jenisTransaksi.value === 'Masuk') {
                penggunaan.disabled = true;  // Matikan dropdown
                penggunaan.value = '';       // Kosongkan isinya
            } else {
                penggunaan.disabled = false; // Hidupkan dropdown
            }
        }

        // 1. Jalankan pengecekan pertama kali saat halaman dimuat
        togglePenggunaan();

        // 2. Jalankan fungsi setiap kali Admin mengubah pilihan Jenis Transaksi
        if (jenisTransaksi && jenisTransaksi.tagName === 'SELECT') {
            jenisTransaksi.addEventListener('change', togglePenggunaan);
        }
    });
</script>
@endsection