<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 (free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5e5ce6;
            --primary-hover: #4b49d1;
            --bg-body: #f6f8fc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-hover: 0 8px 24px rgba(0,0,0,0.08);
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-body);
            color: var(--text-color);
            transition: background 0.3s, color 0.3s;
        }
        .navbar-custom {
            background: var(--card-bg);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            padding: 0.75rem 1.5rem;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.4rem;
        }
        .navbar-brand i {
            margin-right: 0.5rem;
        }
        .navbar-search {
            width: 240px;
            transition: width 0.2s;
        }
        .navbar-search:focus {
            width: 320px;
        }
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-radius: 12px;
            padding: 0.5rem 0;
        }
        .dropdown-item {
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
        }
        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
        }
        .dropdown-item:hover {
            background: #f1f4f9;
        }
        .main-content {
            padding: 2rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .card:hover {
            box-shadow: var(--shadow-hover);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            color: #fff;
        }
        /* Dark mode */
        [data-bs-theme="dark"] {
            --bg-body: #12161e;
            --card-bg: #1e2633;
            --text-color: #e2e8f0;
            --border-color: #2d3748;
        }
        [data-bs-theme="dark"] .navbar-custom {
            background: #1e2633;
            border-bottom: 1px solid #2d3748;
        }
        [data-bs-theme="dark"] .dropdown-menu {
            background: #1e2633;
            color: #e2e8f0;
        }
        [data-bs-theme="dark"] .dropdown-item:hover {
            background: #2d3748;
        }
        /* Utilities */
        .text-primary-custom {
            color: var(--primary);
        }
        .badge-collection {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-search { width: 160px; }
            .navbar-search:focus { width: 200px; }
            .main-content { padding: 1rem; }
        }
    </style>
    @stack('css')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('songs.index') }}">
                <i class="fas fa-music"></i> {{ config('app.name') }}
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('songs.*') ? 'active fw-bold' : '' }}" href="{{ route('songs.index') }}">
                            <i class="fas fa-music me-1"></i>Songs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('collections.*') ? 'active fw-bold' : '' }}" href="{{ route('collections.index') }}">
                            <i class="fas fa-folder me-1"></i>Collections
                        </a>
                    </li>
                </ul>
                <!-- Search form (visible on all screens) -->
                <form action="{{ route('songs.index') }}" method="GET" class="d-flex me-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control navbar-search" placeholder="Search songs..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                <!-- Dark mode toggle & user dropdown -->
                <div class="d-flex align-items-center">
                    <button id="darkModeToggle" class="btn btn-sm btn-outline-secondary me-2" title="Toggle dark mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="dropdown">
                        <a class="dropdown-toggle text-decoration-none d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar me-1">{{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}</span>
                            <span class="d-none d-md-inline text-secondary">{{ Auth::user()->name ?? 'Guest' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <!-- Footer (optional, minimal) -->
    <footer class="text-center text-muted py-3 border-top" style="border-color: var(--border-color) !important;">
        <small>&copy; {{ date('Y') }} {{ config('app.name') }} – Built with <i class="fas fa-heart text-danger"></i></small>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS (dark mode toggle) -->
    <script>
        (function() {
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
        })();
    </script>
    @stack('js')
</body>
</html>
