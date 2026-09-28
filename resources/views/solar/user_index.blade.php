@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Pengajuan Solar</h1>
        <p class="text-secondary mb-0">Buat dan pantau status pengajuan pengambilan solar Anda.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('solar.create') }}" class="btn btn-primary" style="background-color: #d6643c; border-color: #d6643c;">
            <i class="ti ti-plus me-1"></i> Tambah Pengajuan
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">
        {{ session('success') }}
    </div>
@endif

{{-- CEK JIKA USER BELUM PUNYA DATA SAMA SEKALI --}}
@if($transaksi->isEmpty())
    <div class="card shadow-sm border-0 bg-light mt-4">
        <div class="card-body text-center py-5">
            <i class="ti ti-gas-station text-secondary mb-3" style="font-size: 4rem;"></i>
            <h4 class="fw-bold text-dark">Pengajuan Pengambilan Solar</h4>
            <p class="text-secondary mb-4">Belum ada data. Klik tombol di bawah ini untuk mengisi form pengajuan pengambilan solar operasional.</p>
            <a href="{{ route('solar.create') }}" class="btn btn-primary px-4 py-2" style="background-color: #d6643c; border-color: #d6643c;">
                <i class="ti ti-plus me-1"></i> Buat Pengajuan Baru
            </a>
        </div>
    </div>
@else
    {{-- TAMPILKAN TABEL JIKA DATA USER SUDAH ADA --}}
    <form action="{{ route('solar.index') }}" method="GET" class="mb-3 col-md-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari data pengajuan..." value="{{ request('search') }}">
            <button class="btn btn-light border" type="submit"><i class="ti ti-search"></i></button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap table-hover table-accordion">
                <thead class="table-light border-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Penyimpanan</th>
                        <th>Jenis</th>
                        <th>Penggunaan</th>
                        <th>Jumlah (L)</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $index => $item)
                    <tr>
                        <td class="fw-bold">{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($item->tanggal_transaksi)) }}</td>
                        <td>{{ $item->penyimpanan->nama_penyimpanan ?? '-' }}</td>
                        <td>
                            @if($item->jenis_transaksi == 'Masuk')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Masuk</span>
                            @elseif($item->jenis_transaksi == 'Keluar')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Keluar</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">{{ $item->jenis_transaksi }}</span>
                            @endif
                        </td>
                        <td>{{ $item->penggunaan->nama_item ?? '-' }}</td>
                        <td>{{ $item->jumlah_liter }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>
                            @if($item->status == 'Pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($item->status == 'Approved')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status == 'Pending')
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    <a href="{{ route('solar.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('solar.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Batalkan pengajuan ini?')">
                                            <i class="ti ti-trash me-1"></i>Batal
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted" style="font-size: 0.85rem;"><i class="ti ti-lock"></i> Terkunci</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection