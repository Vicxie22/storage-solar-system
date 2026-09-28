@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fs-3 mb-1 app-title">Informasi Stok Solar</h1>
        <p class="text-secondary mb-0">Pantau jumlah stok fisik secara real-time di setiap lokasi penyimpanan.</p>
    </div>
</div>

<div class="row">
    @forelse($penyimpanan as $item)
        @php
            // 1. Logika Kalkulasi Persentase
            // Jika kapasitas belum diatur (0), kita asumsikan 1000 sebagai default agar tidak terjadi error pembagian dengan nol
            $kapasitas = $item->kapasitas_maksimal > 0 ? $item->kapasitas_maksimal : 1000; 
            $persentase = ($item->stok_sekarang / $kapasitas) * 100;
            
            // 2. Batasan Visual
            // Mencegah air meluap ke luar tangki jika stok melebihi kapasitas (maksimal 100%)
            $persentaseVisual = $persentase > 100 ? 100 : ($persentase < 0 ? 0 : $persentase);
            
            // 3. Logika Warna Cairan (Otomatis berubah berdasarkan sisa stok)
            $warnaCairan = 'linear-gradient(to top, #28a745, #5cd08d)'; // Hijau (Aman)
            
            if ($persentaseVisual <= 20) {
                $warnaCairan = 'linear-gradient(to top, #dc3545, #f06a77)'; // Merah (Kritis)
            } elseif ($persentaseVisual <= 50) {
                $warnaCairan = 'linear-gradient(to top, #ffc107, #ffdf7e)'; // Kuning (Waspada)
            }
        @endphp
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center pt-4">
                    <h5 class="fw-bold text-dark mb-1">{{ $item->nama_penyimpanan }}</h5>
                    <p class="text-muted small mb-4"><i class="ti ti-map-pin me-1"></i>{{ $item->lokasi ?? 'Lokasi tidak diketahui' }}</p>
                    
                    <!-- AREA VISUALISASI TANGKI -->
                    <div class="d-flex justify-content-center mb-4">
                        <div style="width: 140px; height: 260px; border: 5px solid #bdc3c7; border-radius: 10px 10px 30px 30px; position: relative; overflow: hidden; background-color: #ecf0f1; box-shadow: inset 0 0 10px rgba(0,0,0,0.1);">
                            
                            <!-- CAIRAN SOLAR -->
                            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: {{ $persentaseVisual }}%; background: {{ $warnaCairan }}; transition: height 1.5s ease-in-out; box-shadow: 0 -3px 6px rgba(0,0,0,0.15);">
                                <!-- Efek pantulan cahaya di permukaan cairan -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: rgba(255,255,255,0.4);"></div>
                            </div>
                            
                            <!-- TEKS PERSENTASE DI TENGAH TANGKI -->
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 900; font-size: 1.5rem; color: #2c3e50; text-shadow: 0px 0px 8px rgba(255,255,255,0.9); z-index: 2;">
                                {{ number_format($persentase, 1) }}%
                            </div>
                        </div>
                    </div>

                    <!-- DETAIL ANGKA STOK -->
                    <div class="row text-center mt-3 border-top pt-3 mx-2">
                        <div class="col-6 border-end">
                            <span class="d-block text-muted small">Stok Saat Ini</span>
                            <span class="fw-bold text-dark fs-6">{{ number_format($item->stok_sekarang, 1) }} L</span>
                        </div>
                        <div class="col-6">
                            <span class="d-block text-muted small">Kapasitas Maks</span>
                            @if($item->kapasitas_maksimal > 0)
                                <span class="fw-bold text-secondary fs-6">{{ number_format($item->kapasitas_maksimal, 1) }} L</span>
                            @else
                                <span class="badge bg-danger mt-1">Belum diatur</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center border-0 shadow-sm">
                Belum ada data lokasi penyimpanan tangki.
            </div>
        </div>
    @endforelse
</div>
@endsection