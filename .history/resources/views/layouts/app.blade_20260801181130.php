<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- CoreUI CSS (compiled) -->
    <link rel="stylesheet" href="{{ asset('css/coreui.css') }}">
    <!-- Google Font (Inter) for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom overrides & enhancements */
        :root {
            --font-family-sans-serif: 'Inter', system-ui, -apple-system, sans-serif;
        }
        body {
            font-family: var(--font-family-sans-serif);
            background: #f4f7fc;
            transition: background 0.3s, color 0.3s;
        }
        .c-sidebar {
            background: linear-gradient(180deg, #1a2332 0%, #0f172a 100%);
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            transition: width 0.3s ease;
        }
        .c-sidebar .c-sidebar-nav-link {
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            margin: 0.2rem 0.8rem;
            transition: all 0.2s;
        }
        .c-sidebar .c-sidebar-nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .c-sidebar .c-sidebar-nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-weight: 600;
        }
        .c-sidebar .c-sidebar-nav-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        .c-header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-bottom: 1px solid #e9ecef;
            padding: 0 1.5rem;
        }
        .c-header .c-header-nav-link {
            color: #495057;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .c-header .c-header-nav-link:hover {
            background: #f1f3f5;
        }
        .c-main {
            padding: 1.5rem;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            padding: 1.25rem 1.5rem;
        }
        /* Dark mode overrides */
        [data-bs-theme="dark"] body {
            background: #1a1d23;
            color: #e9ecef;
        }
        [data-bs-theme="dark"] .c-header {
            background: #212529;
            border-bottom-color: #343a40;
        }
        [data-bs-theme="dark"] .c-header .c-header-nav-link {
            color: #ced4da;
        }
        [data-bs-theme="dark"] .card {
            background: #2c3036;
            color: #e9ecef;
        }
        [data-bs-theme="dark"] .card-header {
            border-bottom-color: #343a40;
        }
        /* Responsive tweaks */
        @media (max-width: 768px) {
            .c-main {
                padding: 0.75rem;
            }
            .controls-row {
                flex-wrap: wrap;
                gap: 0.3rem;
            }
        }
        /* Search bar animation */
        .search-input {
            transition: width 0.3s ease;
            width: 180px;
        }
        .search-input:focus {
            width: 280px;
        }
        /* Avatar */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #6c757d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
        }
    </style>
    @stack('css')
</head>
<body class="c-app">
    <!-- Sidebar -->
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed" id="sidebar">
        <div class="c-sidebar-brand d-flex align-items-center justify-content-center py-3">
            <i class="fas fa-music me-2" style="font-size: 1.8rem;"></i>
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
            <li class="c-sidebar-nav-item mt-4">
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

            <!-- Search Bar (global) -->
            <form action="{{ route('songs.index') }}" method="GET" class="d-none d-md-flex ms-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control search-input" placeholder="Search songs..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <div class="c-header-nav ms-auto d-flex align-items-center">
                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" class="c-header-nav-link" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <a class="c-header-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="avatar me-1">{{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}</span>
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
        // Dark mode toggle
        (function() {
            const toggle = document.getElementById('darkModeToggle');
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
        })();

        // Optional: Auto-close sidebar on mobile after link click
        document.querySelectorAll('.c-sidebar-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    const sidebar = document.getElementById('sidebar');
                    const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
                    if (bsOffcanvas) bsOffcanvas.hide();
                }
            });
        });
    </script>
    @stack('js')
</body>
</html>
