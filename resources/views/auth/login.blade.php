<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Storage Solar PTPN 1</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    
    <style>
        body { background-color: #ffffff; }
        .login-container { max-width: 420px; margin: 0 auto; }
        .login-card { border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border-radius: 12px; padding: 2.5rem; }
        .form-control { border-radius: 6px; padding: 0.6rem 1rem; border: 1px solid #cbd5e1; }
        .form-control:focus { border-color: #d6643c; box-shadow: 0 0 0 0.2rem rgba(214, 100, 60, 0.15); }
        .btn-login { background-color: #d6643c; border-color: #d6643c; color: white; border-radius: 6px; padding: 0.6rem; font-weight: 500; }
        .btn-login:hover { background-color: #be5530; border-color: #be5530; color: white; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="container login-container">
        <div class="card login-card">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/logo-ptpn1.png') }}" alt="PTPN Logo" style="height: 55px; margin-bottom: 12px;">
                <h4 class="fw-bold mb-1" style="color: #1e293b;">Masuk ke aplikasi</h4>
                <p class="text-muted small">Aplikasi Storage Solar</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger small p-2 text-center border-0" style="border-radius: 6px; background-color: #fee2e2; color: #ef4444;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-dark small fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" autofocus required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-dark small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-login w-100">
                    <i class="ti ti-login me-1"></i> Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>