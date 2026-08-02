<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
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
            --sidebar-width: 260px;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-body);
            color: var(--text-color);
            transition: background 0.3s, color 0.3s;
        }
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #0b1926;
            color: #cbd5e1;
            overflow-y: auto;
            z-index: 1050;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            border-bottom: 1px solid #1e293b;
        }
        .sidebar-brand i {
            margin-right: 0.5rem;
            color: var(--primary);
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }
        .sidebar-nav li {
            padding: 0.25rem 1.5rem;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            color: #cbd5e1;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.10);
            color: #fff;
            font-weight: 600;
        }
        .sidebar-nav a i {
            width: 24px;
            text-align: center;
            margin-right: 0.75rem;
        }
        .sidebar-nav .logout-link {
            margin-top: 2rem;
            border-top: 1px solid #1e293b;
            padding-top: 1rem;
        }
        /* Main wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Top navbar (only on desktop) */
        .top-navbar {
            background: var(--card-bg);
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1040;
            box-shadow: var(--shadow-sm);
        }
        .top-navbar .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-color);
        }
        .top-navbar .search-form {
            flex: 1;
            max-width: 400px;
            margin: 0 1.5rem;
        }
        .top-navbar .search-form .form-control {
            border-radius: 20px;
            padding-left: 1rem;
        }
        .top-navbar .user-area {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
        /* Main content */
        .main-content {
            flex: 1;
            padding: 2rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        /* Cards & utilities (same as before) */
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
        .text-primary-custom {
            color: var(--primary);
        }
        .badge-collection {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }
        /* Dark mode */
        [data-bs-theme="dark"] {
            --bg-body: #12161e;
            --card-bg: #1e2633;
            --text-color: #e2e8f0;
            --border-color: #2d3748;
        }
        [data-bs-theme="dark"] .top-navbar {
            background: #1e2633;
            border-bottom-color: #2d3748;
        }
        [data-bs-theme="dark"] .sidebar {
            background: #0f172a;
        }
        [data-bs-theme="dark"] .sidebar-nav a {
            color: #94a3b8;
        }
        [data-bs-theme="dark"] .sidebar-nav a:hover {
            background: rgba(255,255,255,0.05);
            color: #e2e8f0;
        }
        /* Responsive: sidebar becomes offcanvas on small screens */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .top-navbar .hamburger {
                display: block;
            }
            .top-navbar .search-form {
                max-width: 200px;
                margin: 0 0.5rem;
            }
        }
        @media (max-width: 576px) {
            .top-navbar .search-form {
                display: none;
            }
            .main-content {
                padding: 1rem;
            }
        }
        /* Offcanvas overlay (mobile) */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            z-index: 1045;
        }
        .sidebar-overlay.show {
            display: block;
        }
    </style>
    @stack('css')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-music"></i> {{ config('app.name') }}
        </div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('songs.index') }}" class="{{ request()->routeIs('songs.*') ? 'active' : '' }}">
                    <i class="fas fa-music"></i> Songs
                </a>
            </li>
            <li>
                <a href="{{ route('collections.index') }}" class="{{ request()->routeIs('collections.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i> Collections
                </a>
            </li>
            <li class="logout-link">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </li>
        </ul>
    </div>

    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search form -->
            <form action="{{ route('songs.index') }}" method="GET" class="search-form d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search songs..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <!-- User area -->
            <div class="user-area">
                <button id="darkModeToggle" class="btn btn-sm btn-outline-secondary" title="Toggle dark mode">
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
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </div>

        <!-- Footer (optional) -->
        <footer class="text-center text-muted py-3 border-top" style="border-color: var(--border-color) !important;">
            <small>&copy; {{ date('Y') }} {{ config('app.name') }} – Built with <i class="fas fa-heart text-danger"></i></small>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        (function() {
            // Sidebar toggle (mobile)
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerBtn');

            function toggleSidebar(show) {
                if (show === undefined) {
                    sidebar.classList.toggle('open');
                } else if (show) {
                    sidebar.classList.add('open');
                } else {
                    sidebar.classList.remove('open');
                }
                overlay.classList.toggle('show', sidebar.classList.contains('open'));
            }

            if (hamburger) {
                hamburger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            overlay.addEventListener('click', function() {
                toggleSidebar(false);
            });

            // Close sidebar on link click (mobile)
            document.querySelectorAll('.sidebar-nav a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        toggleSidebar(false);
                    }
                });
            });

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

            // Adjust main wrapper margin if sidebar is fixed? Already handled via CSS.
        })();
    </script>
    @stack('js')
</body>
</html>
