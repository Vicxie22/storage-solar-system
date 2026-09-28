@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Pencatatan Solar</h1>
        <p class="text-secondary mb-0">Kelola pengajuan dan pencatatan pengambilan solar operasional.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('solar.create') }}" class="btn btn-primary" style="background-color: #d6643c; border-color: #d6643c;">
            <i class="ti ti-plus me-1"></i> Tambah Catatan
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('solar.index') }}" method="GET" class="mb-3 col-md-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari keterangan atau penggunaan..." value="{{ request('search') }}">
        <button class="btn btn-light border" type="submit"><i class="ti ti-search"></i></button>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        {{-- Hapus text-nowrap dari table, tambahkan align-middle agar rata tengah vertikal --}}
        <table class="table mb-0 table-hover table-accordion align-middle">
            <thead class="table-light border-light">
                <tr>
                    {{-- Berikan min-width agar kolom tidak saling gencet --}}
                    <th style="width: 5%">No</th>
                    <th style="min-width: 100px;">Tanggal</th>
                    <th style="min-width: 130px;">Penyimpanan</th>
                    <th style="min-width: 100px;">Jenis</th>
                    <th style="min-width: 130px;">Penggunaan</th>
                    <th style="min-width: 100px;">Jumlah (L)</th>
                    <th style="min-width: 150px;">Keterangan</th>
                    <th style="min-width: 200px;">Informasi Edit</th>
                    <th style="min-width: 100px;">Status</th>
                    <th class="text-center" style="min-width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $index => $item)
                <tr>
                    <td class="fw-bold">{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($item->tanggal_transaksi)) }}</td>
                    <td>{{ $item->penyimpanan->nama_penyimpanan ?? '-' }}</td>
                    <td>
                        {{-- Bungkus badge agar tidak berantakan jika ada 2 badge --}}
                        <div class="d-flex flex-column align-items-start gap-1">
                            @if($item->jenis_transaksi == 'Masuk')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Masuk</span>
                            @elseif($item->jenis_transaksi == 'Keluar')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Keluar</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">{{ $item->jenis_transaksi }}</span>
                            @endif

                            @if(!empty($item->informasi_edit))
                                <span class="badge bg-info bg-opacity-10 text-info border border-info">Diedit</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $item->penggunaan->nama_item ?? '-' }}</td>
                    <td class="fw-semibold">{{ $item->jumlah_liter }}</td>
                    
                    {{-- Tambahkan text-wrap agar teks panjang turun ke bawah dengan rapi --}}
                    <td class="text-wrap text-muted">{{ $item->keterangan ?? '-' }}</td>
                    
                    {{-- Perkecil ukuran font khusus informasi edit agar tidak terlalu memakan tempat --}}
                    <td class="text-wrap text-muted" style="font-size: 0.85rem; line-height: 1.4;">
                        {!! $item->informasi_edit ? nl2br(e($item->informasi_edit)) : '-' !!}
                    </td>
                    
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
                        {{-- Ganti flex-wrap menjadi flex-nowrap agar tombol Edit & Hapus selalu bersebelahan --}}
                        <div class="d-flex gap-1 justify-content-center flex-nowrap">
                            <a href="{{ route('solar.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <form action="{{ route('solar.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus catatan ini?')">
                                    <i class="ti ti-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Belum ada data pencatatan solar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection