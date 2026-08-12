@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Informasi Stok Solar</h1>
        <p class="text-secondary mb-0">Pantau jumlah stok fisik secara real-time di setiap lokasi penyimpanan.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap table-hover">
            <thead class="table-light border-light">
                <tr>
                    <th>No</th>
                    <th>Nama Penyimpanan</th>
                    <th>Lokasi</th>
                    <th>Stok Saat Ini (Liter)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyimpanan as $index => $item)
                <tr>
                    <td class="fw-bold">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_penyimpanan }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td class="fw-bold text-primary fs-5">{{ $item->stok_sekarang }} L</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection