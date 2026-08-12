@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">
            {{ auth()->user()->role === 'Admin' ? 'Riwayat Transaksi' : 'Riwayat Pengajuan Saya' }}
        </h1>
        <p class="text-secondary mb-0">
            {{ auth()->user()->role === 'Admin' ? 'Catatan riwayat seluruh transaksi masuk, keluar, dan penyesuaian stok.' : 'Catatan riwayat pengajuan pengambilan solar yang pernah Anda lakukan.' }}
        </p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap table-hover">
            <thead class="table-light border-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Penyimpanan</th>
                    <th>Jenis</th>
                    <th>Jumlah (L)</th>
                    <th>Penggunaan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $item)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($item->tanggal_transaksi)) }}</td>
                    <td>{{ $item->penyimpanan->nama_penyimpanan ?? '-' }}</td>
                    <td>
                        @if($item->jenis_transaksi == 'Masuk')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success">Masuk</span>
                        @elseif($item->jenis_transaksi == 'Keluar')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Keluar</span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info border border-info">Penyesuaian Stok</span>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $item->jumlah_liter }}</td>
                    <td>{{ $item->penggunaan->nama_item ?? '-' }}</td>

                    <td>
                        @if($item->status == 'Pending')
                            <span class="badge bg-warning text-dark">Menunggu Admin</span>
                        @elseif($item->status == 'Approved')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($item->status == 'Rejected')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">{{ $item->status }}</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#modalDetail-{{ $item->id }}">
                            <i class="ti ti-eye text-secondary"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- Modal Detail -->
                <div class="modal fade text-start" id="modalDetail-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title fw-bold">Detail Transaksi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Tanggal</span>
                                        <strong>{{ date('d F Y', strtotime($item->tanggal_transaksi)) }}</strong>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Jenis Transaksi</span>
                                        <strong>{{ $item->jenis_transaksi }}</strong>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <span class="text-muted d-block mb-1">Keterangan / Detail Perubahan:</span>
                                        <div class="bg-light p-3 rounded text-dark" style="font-size: 0.9rem;">
                                            {!! nl2br(e($item->keterangan ?? 'Tidak ada keterangan tambahan.')) !!}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $history->links() }}</div>
@endsection