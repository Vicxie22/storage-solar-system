@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Master Lokasi Penyimpanan</h1></div>
    </div>
    
    <div class="row">
        <!-- Form Tambah Data -->
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Tambah Penyimpanan</h3></div>
                <form action="{{ route('penyimpanan.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Penyimpanan</label>
                            <input type="text" name="nama_penyimpanan" class="form-control" required placeholder="Contoh: Tangki Utama">
                        </div>
                        <div class="form-group">
                            <label>Lokasi / Daerah</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Area Pabrik 1">
                        </div>
                        <!-- PENAMBAHAN ELEMEN INPUT KAPASITAS -->
                        <div class="form-group">
                            <label>Maksimal Kapasitas (Liter)</label>
                            <input type="number" step="0.1" name="kapasitas_maksimal" class="form-control" required placeholder="Contoh: 5000">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Daftar Lokasi Penyimpanan</h3></div>
                <div class="card-body p-0">
    @if(session('success'))
        <div class="alert alert-success m-2">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger m-2">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penyimpanan</th>
                <th>Lokasi</th>
                <th>Kapasitas (L)</th>
                <th>Aksi</th> <!-- Ekspansi Header Kolom -->
            </tr>
        </thead>
        <tbody>
            @forelse($penyimpanan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_penyimpanan }}</td>
                <td>{{ $item->lokasi ?? '-' }}</td>
                <td>{{ number_format($item->kapasitas_maksimal ?? 0, 1) }}</td>
                <td>
                    <!-- Formulir Transmisi Permintaan Penghapusan -->
                    <form action="{{ route('penyimpanan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tangki ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="background-color: #dc3545; border-color: #dc3545;">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Entitas data belum tersedia.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
            </div>
        </div>
    </div>
</div>
@endsection