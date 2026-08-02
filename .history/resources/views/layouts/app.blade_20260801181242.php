<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- CoreUI CSS (compiled) -->
    <link rel="stylesheet" href="{{ asset('css/coreui.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Font (optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <style>
        /* Essential overrides */
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
        }
        .c-sidebar {
            background: #0b1926;
        }
        .c-sidebar .c-sidebar-nav-link {
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: background 0.15s;
        }
        .c-sidebar .c-sidebar-nav-link:hover {
            background: rgba(255,255,255,0.08);
        }
        .c-sidebar .c-sidebar-nav-link.active {
            background: rgba(255,255,255,0.12);
            border-right: 3px solid #3399ff;
        }
        .c-header {
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .c-header .c-header-nav-link {
            color: #4a5a6e;
            padding: 0.5rem 0.75rem;
        }
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2d3748;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .c-main {
            padding: 1.5rem;
        }
        .card {
            border-radius: 12px;
            border: 1px solid #e9edf2;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e9edf2;
            font-weight: 600;
        }
        /* Dark mode (optional) */
        [data-bs-theme="dark"] body {
            background: #1a1e26;
            color: #e2e8f0;
        }
        [data-bs-theme="dark"] .c-header {
            background: #1e293b;
            border-bottom-color: #334155;
        }
        [data-bs-theme="dark"] .c-header .c-header-nav-link {
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .card {
            background: #1e293b;
            border-color: #334155;
        }
        [data-bs-theme="dark"] .card-header {
            border-bottom-color: #334155;
        }
        /* Responsive tweaks */
        @media (max-width: 768px) {
            .c-main { padding: 1rem; }
        }
        /* Sticky controls (if used on show page) */
        .controls-row {
            position: sticky;
            top: 56px;
            z-index: 1020;
            background: #fff;
            border-bottom: 1px solid #e9edf2;
        }
        [data-bs-theme="dark"] .controls-row {
            background: #1e293b;
            border-bottom-color: #334155;
        }
    </style>
    @stack('css')
</head>
<body class="c-app">
    <!-- Sidebar -->
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed" id="sidebar">
        <div class="c-sidebar-brand d-flex align-items-center justify-content-center py-3">
            <i class="fas fa-music me-2" style="font-size: 1.5rem;"></i>
            <span class="fw-bold">{{ config('app.name') }}</span>
        </div>
        <ul class="c-sidebar-nav">
            <li class="c-sidebar-nav-item">
                <a href="{{ route('songs.index') }}" class="c-sidebar-nav-link {{ request()->routeIs('songs.*') ? 'active' : '' }}">
                    <i class="c-sidebar-nav-icon fas fa-music"></i> Songs
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a href="{{ route('collections.index') }}" class="c-sidebar-nav-link {{ request()->routeIs('collections.*') ? 'active' : '' }}">
                    <i class="c-sidebar-nav-icon fas fa-folder"></i> Collections
                </a>
            </li>
            <li class="c-sidebar-nav-item mt-3">
                <a href="{{ route('logout') }}" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="c-sidebar-nav-icon fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </li>
        </ul>
    </div>

    <!-- Wrapper -->
    <div class="c-wrapper c-fixed-components">
        <!-- Header -->
        <header class="c-header c-header-light c-header-fixed">
            <button class="c-header-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#headerNav" aria-controls="headerNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search -->
            <form action="{{ route('songs.index') }}" method="GET" class="d-none d-md-flex ms-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search songs..." value="{{ request('search') }}" style="width: 200px;">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <div class="c-header-nav ms-auto d-flex align-items-center">
                <!-- Dark mode toggle (optional – remove if not needed) -->
                <button id="darkModeToggle" class="c-header-nav-link" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>

                <!-- User dropdown -->
                <div class="dropdown">
                    <a class="c-header-nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="avatar me-2">{{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}</span>
                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Guest' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Body -->
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="c-footer">
            <div>&copy; {{ date('Y') }} {{ config('app.name') }} – Made with <i class="fas fa-heart text-danger"></i></div>
        </footer>
    </div>

    <!-- CoreUI JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/js/coreui.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        (function() {
            // Dark mode toggle
            const toggle = document.getElementById('darkModeToggle');
            if (toggle) {
                const html = document.documentElement;
                const icon = toggle.querySelector('i');
                function setTheme(theme) {
                    html.setAttribute('data-bs-theme', theme);
                    icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                    localStorage.setItem('theme', theme);
                }
                const saved = localStorage.getItem('theme') || 'light';
                setTheme(saved);
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const current = html.getAttribute('data-bs-theme');
                    setTheme(current === 'dark' ? 'light' : 'dark');
                });
            }

            // Auto-close sidebar on mobile after link click
            document.querySelectorAll('.c-sidebar-nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        const sidebar = document.getElementById('sidebar');
                        const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
                        if (bsOffcanvas) bsOffcanvas.hide();
                    }
                });
            });
        })();
    </script>
    @stack('js')
</body>
</html>
