<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Storage Solar | PTPN 1</title>
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    
    <!-- Tabler Icons (Sistem baru menggunakan Tabler, bukan FontAwesome) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    
    <!-- Bootstrap & Custom CSS (Pastikan file ini sudah ada di folder public/assets/css) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    
    <style>
        .content .container-fluid { max-width: 1600px; }
        .table th { white-space: nowrap; }
        .app-title { line-height: 1.15; }
        .submenu { list-style: none; margin: .15rem 0 .35rem 0; padding: 0 0 0 2.65rem; }
        .submenu .nav-link { padding: .35rem .85rem; font-size: .875rem; }
        .submenu-toggle .ti-chevron-down { transition: transform .2s ease; }
        .submenu-toggle[aria-expanded="true"] .ti-chevron-down { transform: rotate(180deg); }

        /* Responsive Table Accordion bawaan dari sistem Mess */
        @media (max-width: 767.98px) {
            .table-accordion thead { display: none !important; }
            .table-accordion tbody tr {
                display: flex !important; flex-direction: column !important; margin-bottom: 1.25rem !important;
                border: 1px solid #e2e8f0 !important; border-radius: 0.75rem !important;
                background-color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
                padding-bottom: 0.5rem !important; white-space: normal !important;
            }
            .table-accordion tbody td {
                display: flex !important; justify-content: space-between !important; align-items: center !important;
                padding: 0.75rem 1rem !important; border-bottom: 1px solid #f8fafc !important;
                text-align: right !important; font-size: 0.9rem !important; white-space: normal !important;
            }
            .table-accordion tbody td:last-child { border-bottom: none !important; }
            .table-accordion tbody td::before {
                content: attr(data-label) !important; font-weight: 600 !important; color: #64748b !important;
                text-align: left !important; margin-right: 1rem !important; flex-shrink: 0 !important; max-width: 40% !important;
            }
            .table-accordion tbody td.detail-data { display: none !important; }
            .table-accordion tbody tr.is-expanded td.detail-data { display: flex !important; animation: fadeIn 0.3s ease-in-out !important; }
            .table-accordion tbody td.toggle-cell {
                background-color: #f8fafc !important; border-bottom: 2px solid #e2e8f0 !important;
                font-size: 1rem !important; cursor: pointer !important; border-radius: 0.75rem 0.75rem 0 0 !important;
            }
            .table-accordion tbody td.toggle-cell::before { color: #0f172a !important; }
            .table-accordion tbody td.toggle-cell .toggle-icon { transition: transform 0.3s ease !important; margin-left: 0.5rem !important; font-size: 1.25rem !important; color: #3b82f6 !important; }
            .table-accordion tbody tr.is-expanded td.toggle-cell .toggle-icon { transform: rotate(180deg) !important; }
            .table-accordion tbody td.action-data {
                order: 99 !important; justify-content: center !important; border-top: 1px dashed #cbd5e1 !important;
                margin-top: 0.5rem !important; padding-top: 1rem !important;
            }
            .table-accordion tbody td.action-data::before { display: none !important; }
            .table-accordion tbody td.action-data > div { display: flex !important; flex-wrap: wrap !important; gap: 0.5rem !important; width: 100% !important; justify-content: center !important; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        }
    </style>
</head>
<body>

<div id="overlay" class="overlay"></div>

<!-- Topbar -->
<nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar px-3">
    <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm">
        <i class="ti ti-layout-sidebar-left-expand"></i>
    </button>
    <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
        <i class="ti ti-layout-sidebar-left-expand"></i>
    </button>
    <div class="ms-auto">
        <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
            @auth
            @php
                // Mengambil huruf pertama dari nama user untuk dijadikan Inisial Avatar
                $initial = strtoupper(substr(auth()->user()->name, 0, 1));
            @endphp
            <li class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-none d-md-block text-end">
                        <span class="d-block fw-bold" style="color: #334155;">{{ auth()->user()->name }}</span>
                    </span>
                    <!-- Avatar Lingkaran Inisial -->
                    <div class="d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; border-radius: 50%; background-color: #fbeae5; color: #d6643c;">
                        {{ $initial }}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0 shadow" style="min-width: 250px; border-radius: 8px; margin-top: 12px; border: 1px solid #e2e8f0;">
                    <div class="d-flex gap-3 align-items-center border-bottom px-3 py-3">
                        <div class="d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px; border-radius: 50%; background-color: #fbeae5; color: #d6643c;">
                            {{ $initial }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold" style="color: #1e293b;">{{ auth()->user()->name }}</h6>
                        </div>
                    </div>
                    <div class="p-3 d-grid gap-2">
                        <!-- Tombol Ganti Password (Arahkan ke # untuk saat ini karena fiturnya belum dibuat) -->
                        <a href="#" class="btn btn-sm text-start py-2 border-0 fw-medium" style="background-color: #f1f5f9; color: #0f172a; font-size: 14px; border-radius: 6px;">
                            <i class="ti ti-key me-2"></i> Ganti Password
                        </a>
                        <!-- Tombol Logout -->
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start py-2 fw-medium" style="border-radius: 6px; font-size: 14px;">
                                <i class="ti ti-logout me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </li>
            @else
            <li>
                <a href="{{ route('login') }}" class="btn btn-sm text-white px-3" style="background-color: #d6643c; border-radius: 6px;"><i class="ti ti-login me-1"></i> Login</a>
            </li>
            @endauth
        </ul>
    </div>
</nav>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="logo-area" style="padding: 15px 20px; border-bottom: 1px solid #f0f0f0;">
        <a href="{{ route('solar.index') }}" class="d-inline-flex align-items-center text-decoration-none">
            <img src="{{ asset('assets/images/logo-ptpn1.png') }}" alt="PTPN 1" style="height:48px; width:auto; object-fit:contain;">
            <span class="ms-2 fw-bold text-dark fs-5">Storage Solar</span>
        </a>
    </div>
    
    <ul class="nav flex-column mt-3">
        <li class="px-4 py-2"><small class="nav-text text-muted fw-bold">TRANSAKSI</small></li>
        <li>
            <a class="nav-link {{ request()->routeIs('solar.index') ? 'active' : '' }}" href="{{ route('solar.index') }}">
                <i class="ti ti-gas-station"></i>
                <span class="nav-text">Pencatatan Solar</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ request()->routeIs('solar.stok') ? 'active' : '' }}" href="{{ route('solar.stok') }}">
                <i class="ti ti-droplet"></i>
                <span class="nav-text">Informasi Stok</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('solar.history') ? 'active' : '' }}" href="{{ route('solar.history') }}">
                <i class="ti ti-history"></i>
                <span class="nav-text">Riwayat</span>
            </a>
        </li>

        @if(auth()->user()->role == 'Admin')

        <li>
            <a class="nav-link {{ request()->routeIs('solar.approval') ? 'active' : '' }}" href="{{ route('solar.approval') }}">
                <i class="ti ti-checkup-list"></i>
                <span class="nav-text">Approval</span>
            </a>
        </li>

        <li class="px-4 pt-4 pb-2"><small class="nav-text text-muted fw-bold">DATA MASTER</small></li>
        <li>
            <a class="nav-link submenu-toggle {{ request()->routeIs('penyimpanan.*') || request()->routeIs('penggunaan.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#submenu-master" role="button">
                <i class="ti ti-database"></i>
                <span class="nav-text">Master Data</span>
                <i class="ti ti-chevron-down ms-auto nav-text"></i>
            </a>
            <ul class="submenu collapse {{ request()->routeIs('penyimpanan.*') || request()->routeIs('penggunaan.*') ? 'show' : '' }}" id="submenu-master">
                <li><a class="nav-link {{ request()->routeIs('penyimpanan.*') ? 'active' : '' }}" href="{{ route('penyimpanan.index') }}"><span class="nav-text">Lokasi Penyimpanan</span></a></li>
                <li><a class="nav-link {{ request()->routeIs('penggunaan.*') ? 'active' : '' }}" href="{{ route('penggunaan.index') }}"><span class="nav-text">Item Penggunaan</span></a></li>
            </ul>
        </li>

        @endif

    </ul>
</aside>

<!-- Main Content -->
<main id="content" class="content py-10" style="margin-top: 60px;">
    <div class="container-fluid">
        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Sidebar
        document.getElementById('toggleBtn')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('content').classList.toggle('collapsed');
        });
        
        // Mobile Table Accordion
        document.body.addEventListener('click', function (e) {
            const toggleCell = e.target.closest('.table-accordion td.toggle-cell');
            if (!toggleCell || window.innerWidth >= 768) return;

            const tr = toggleCell.closest('tr');
            tr.classList.toggle('is-expanded');
        });
    });
</script>
</body>
</html>