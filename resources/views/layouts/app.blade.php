<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f0f2f5; }
        .sidebar {
            width: var(--sidebar-width); min-height: 100vh; position: fixed; top: 0; left: 0;
            background: linear-gradient(135deg, #1e3a5f 0%, #152a45 100%);
            color: #fff; z-index: 1000; transition: transform .3s;
        }
        .sidebar .brand { padding: 1.5rem 1.25rem; font-size: 1.3rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar .nav-link {
            color: rgba(255,255,255,.7); padding: .65rem 1.25rem; font-size: .9rem;
            border-left: 3px solid transparent; transition: all .2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,.08); border-left-color: #4da3ff;
        }
        .sidebar .nav-link i { width: 24px; margin-right: 8px; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .top-bar { background: #fff; padding: .75rem 1.5rem; border-bottom: 1px solid #e3e6ea; }
        .content-area { padding: 1.5rem; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); border-radius: .5rem; }
        .stat-card { border-left: 4px solid; }
        .stat-card.primary { border-left-color: #4da3ff; }
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.info { border-left-color: #17a2b8; }
        .btn-primary { background: #1e3a5f; border-color: #1e3a5f; }
        .btn-primary:hover { background: #152a45; border-color: #152a45; }
        .table th { font-weight: 600; font-size: .85rem; text-transform: uppercase; color: #6c757d; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <i class="bi bi-cash-stack"></i> Payroll X
        </div>
        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('employees*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <i class="bi bi-people"></i> Employees
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('payroll*') ? 'active' : '' }}" href="{{ route('payroll.index') }}">
                    <i class="bi bi-calculator"></i> Payroll
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('loans*') ? 'active' : '' }}" href="{{ route('loans.index') }}">
                    <i class="bi bi-credit-card"></i> Loans
                </a>
            </li>
            @role('Admin')
            <li class="nav-item mt-3">
                <small class="text-muted px-3 text-uppercase" style="font-size:.7rem">Administration</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-person-gear"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            @endrole
        </ul>
    </nav>

    <div class="main-content">
        <div class="top-bar d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-sm btn-outline-secondary d-md-none me-2" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <strong>@yield('title', 'Dashboard')</strong>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ auth()->user()->name }}</span>
                <span class="badge bg-primary">{{ auth()->user()->roles->first()?->name ?? 'User' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
