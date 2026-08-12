@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Master Item Penggunaan</h1>
        <p class="text-secondary mb-0">Kelola daftar kendaraan, genset, atau mesin yang menggunakan solar.</p>
    </div>
</div>

<div class="row">
    <!-- Form Tambah Item -->
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Item</h5>

                <form action="{{ route('penggunaan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" id="kategoriSelect" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Kendaraan">Kendaraan</option>
                            <option value="Genset">Genset</option>
                            <option value="Mesin Pabrik">Mesin Pabrik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" id="labelItem">Nama Item / Plat Nomor</label>
                        <input type="text" name="nama_item" id="inputItem" class="form-control" placeholder="Contoh: BK 1234 XX" required>
                        <small class="text-muted d-block mt-1" id="helpText">Masukkan plat nomor kendaraan operasional.</small>
                    </div>

                    <button type="submit" class="btn btn-primary" style="background-color: #d6643c; border-color: #d6643c;">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Item -->
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Daftar Item Penggunaan</h5>
                <div class="table-responsive">
                    <table class="table mb-0 text-nowrap table-hover">
                        <thead class="table-light border-light">
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Nama Item / Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penggunaan ?? [] as $index => $item)
                            <tr>
                                <td class="fw-bold">{{ $index + 1 }}</td>
                                <td>
                                    @if($item->kategori == 'Kendaraan')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Kendaraan</span>
                                    @elseif($item->kategori == 'Genset')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Genset</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">{{ $item->kategori }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->nama_item }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Data masih kosong.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kategoriSelect');
        const labelItem = document.getElementById('labelItem');
        const inputItem = document.getElementById('inputItem');
        const helpText = document.getElementById('helpText');

        kategoriSelect.addEventListener('change', function () {
            const val = this.value;

            if (val === 'Genset') {
                labelItem.textContent = 'Nama Genset & Lokasi Area';
                inputItem.placeholder = 'Contoh: Genset Area Pabrik / Genset Mess A';
                helpText.textContent = 'Tuliskan nama unit genset beserta lokasi/area penempatannya.';
            } else if (val === 'Kendaraan') {
                labelItem.textContent = 'Plat Nomor Kendaraan';
                inputItem.placeholder = 'Contoh: BK 1234 XX';
                helpText.textContent = 'Masukkan plat nomor kendaraan operasional dinas/lapangan.';
            } else if (val === 'Mesin Pabrik') {
                labelItem.textContent = 'Nama Mesin & Area';
                inputItem.placeholder = 'Contoh: Mesin Boiler Area Pengolahan';
                helpText.textContent = 'Tuliskan nama mesin atau peralatan pabrik.';
            } else {
                labelItem.textContent = 'Nama Item / Keterangan';
                inputItem.placeholder = 'Contoh: Pemakaian Khusus';
                helpText.textContent = 'Tuliskan detail peruntukan solar.';
            }
        });
    });
</script>
@endsection