@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Approval Pengeluaran Solar</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Menunggu Persetujuan</h3>
        </div>
        
        <div class="card-body p-0">  
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Tanggal</th>
                        <th>Dari Penyimpanan</th>
                        <th>Untuk Penggunaan</th>
                        <th>Jumlah (L)</th>
                        <th>Keterangan</th>
                        <th>Lampiran Invoice</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($item->tanggal_transaksi)) }}</td>
                        <td>{{ $item->penyimpanan->nama_penyimpanan ?? '-'}}</td>
                        <td>{{ $item->penggunaan->nama_item ?? '-' }}</td>
                        <td>{{ $item->jumlah_liter }}</td>
                        <td>{{ $item->keterangan }}</td>
                        
                        <!-- IMPLEMENTASI KOLOM LAMPIRAN INVOICE -->
                        <td>
                            @if($item->file_invoice)
                                <a href="{{ asset('storage/' . $item->file_invoice) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-file-alt"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        <!-- KOLOM AKSI PERSETUJUAN -->
                        <td>
                            <form action="{{ route('solar.approval.update', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Approved">
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui transaksi ini?')">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </form>
                            
                            <form action="{{ route('solar.approval.update', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak transaksi ini?')">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <!-- PENYESUAIAN COLSPAN MENJADI 8 -->
                        <td colspan="8" class="text-center">Tidak ada transaksi yang menunggu persetujuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection