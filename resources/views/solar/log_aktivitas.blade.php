@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Log Aktivitas Sistem</h1>
        <p class="text-secondary mb-0">Pantau seluruh rekam jejak aktivitas yang dilakukan oleh pengguna.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap table-hover">
            <thead class="table-light border-light">
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Keterangan Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <span class="fw-bold">{{ $log->created_at->format('d/m/Y') }}</span><br>
                        <small class="text-muted">{{ $log->created_at->format('H:i:s') }} WIB</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            <i class="ti ti-user me-1"></i> {{ $log->user->name ?? 'Sistem' }}
                        </span>
                    </td>
                    <td class="fw-bold text-primary">{{ $log->aksi }}</td>
                    <td class="text-wrap" style="min-width: 250px;">{{ $log->deskripsi }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada aktivitas yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Pagination -->
<div class="mt-3">
    {{ $logs->links() }}
</div>
@endsection