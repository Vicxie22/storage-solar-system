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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penyimpanan</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penyimpanan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama_penyimpanan }}</td>
                                <td>{{ $item->lokasi ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center">Data masih kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection