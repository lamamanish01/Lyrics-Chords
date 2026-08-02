<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            --header-height: 64px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-body);
            color: var(--text-color);
            transition: background 0.3s, color 0.3s;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* ---- Sidebar (desktop) ---- */
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
            transform: translateX(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 2px 0 12px rgba(0,0,0,0.08);
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 0;
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            border-bottom: 1px solid #1e293b;
        }
        .sidebar-brand i { margin-right: 0.5rem; color: var(--primary); }
        .sidebar-nav {
            list-style: none;
            padding: 0.5rem 1rem;
            margin: 0;
        }
        .sidebar-nav li { margin-bottom: 0.25rem; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            color: #cbd5e1;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            font-size: 0.95rem;
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
            font-size: 1rem;
        }
        .sidebar-nav .logout-link {
            margin-top: 2rem;
            border-top: 1px solid #1e293b;
            padding-top: 1rem;
        }

        /* ---- Overlay (mobile) ---- */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            z-index: 1045;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.show { display: block; opacity: 1; }

        /* ---- Main wrapper ---- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---- Top navbar ---- */
        .top-navbar {
            background: var(--card-bg);
            padding: 0.5rem 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1040;
            box-shadow: var(--shadow-sm);
            min-height: var(--header-height);
        }
        .top-navbar .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-color);
            padding: 0.25rem 0.5rem;
            line-height: 1;
        }
        .top-navbar .search-form {
            flex: 1;
            max-width: 400px;
            margin: 0 1rem;
        }
        .top-navbar .search-form .input-group {
            border-radius: 20px;
            overflow: hidden;
            background: var(--bg-body);
        }
        .top-navbar .search-form .form-control {
            border: none;
            background: transparent;
            padding-left: 1rem;
            font-size: 0.9rem;
        }
        .top-navbar .search-form .btn {
            border: none;
            background: transparent;
            color: #6c757d;
        }
        .top-navbar .search-form .form-control:focus { box-shadow: none; }
        .top-navbar .user-area {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
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
        .dropdown-item:hover { background: #f1f4f9; }

        /* ---- Main content ---- */
        .main-content {
            flex: 1;
            padding: 1.5rem 1.5rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* ---- Cards & utilities ---- */
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
        .text-primary-custom { color: var(--primary); }
        .badge-collection {
            padding: 0.35rem 0.7rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* ---- Dark mode ---- */
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
        [data-bs-theme="dark"] .sidebar { background: #0f172a; }
        [data-bs-theme="dark"] .sidebar-nav a { color: #94a3b8; }
        [data-bs-theme="dark"] .sidebar-nav a:hover {
            background: rgba(255,255,255,0.05);
            color: #e2e8f0;
        }
        [data-bs-theme="dark"] .dropdown-menu {
            background: #1e2633;
            color: #e2e8f0;
        }
        [data-bs-theme="dark"] .dropdown-item:hover { background: #2d3748; }
        [data-bs-theme="dark"] .top-navbar .search-form .input-group {
            background: #2d3748;
        }
        [data-bs-theme="dark"] .top-navbar .search-form .form-control {
            color: #e2e8f0;
        }

        /* ========== RESPONSIVE ========== */
        /* Tablets and small desktops */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .top-navbar .hamburger { display: block; }
            .top-navbar .search-form {
                max-width: 200px;
                margin: 0 0.5rem;
            }
        }

        /* Phones */
        @media (max-width: 576px) {
            .top-navbar {
                padding: 0.4rem 0.75rem;
            }
            .top-navbar .search-form {
                display: none;
                position: absolute;
                left: 0;
                top: 100%;
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0.5rem 1rem;
                background: var(--card-bg);
                border-bottom: 1px solid var(--border-color);
                z-index: 1050;
            }
            .top-navbar .search-form.expanded {
                display: flex;
            }
            .top-navbar .search-form .input-group {
                border-radius: 20px;
                background: var(--bg-body);
            }
            .top-navbar .search-toggle {
                display: block !important;
            }
            .top-navbar .user-area .dropdown-toggle span.d-none.d-md-inline {
                display: none !important;
            }
            .top-navbar .user-area .avatar {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }
            .main-content {
                padding: 1rem 0.75rem;
            }
            .card-header {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            .controls-row {
                flex-direction: column;
                align-items: stretch !important;
                gap: 0.5rem !important;
                padding: 0.75rem !important;
            }
            .controls-row .btn,
            .controls-row .badge,
            .controls-row .form-range {
                width: 100%;
                text-align: center;
            }
            .controls-row .form-range { width: 100% !important; }
            .controls-row .ms-auto {
                margin-left: 0 !important;
                text-align: center;
            }
            .modal-body .form-range {
                width: 80px !important;
            }
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }
            .h1, .h2, .h3, .h4, .h5 {
                font-size: 1.2rem;
            }
        }

        /* Search toggle (visible only on phones) */
        .search-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-color);
            padding: 0.25rem 0.5rem;
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
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
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

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search form (desktop & tablet) -->
            <form action="{{ route('songs.index') }}" method="GET" class="search-form" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search songs..." value="{{ request('search') }}">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <!-- Search toggle (mobile) -->
            <button class="search-toggle" id="searchToggle" aria-label="Toggle search">
                <i class="fas fa-search"></i>
            </button>

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
                        <li><a class="dropdown-item" href="{{ route('users.') }}"><i class="fas fa-user"></i>Profile</a></li>
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

        <!-- Footer -->
        <footer class="text-center text-muted py-3 border-top" style="border-color: var(--border-color) !important;">
            <small>&copy; {{ date('Y') }} {{ config('app.name') }} – Built with <i class="fas fa-heart text-danger"></i></small>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ----- Sidebar toggle (mobile) -----
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerBtn');

            function toggleSidebar(force) {
                if (typeof force === 'boolean') {
                    if (force) sidebar.classList.add('open');
                    else sidebar.classList.remove('open');
                } else {
                    sidebar.classList.toggle('open');
                }
                const nowOpen = sidebar.classList.contains('open');
                overlay.classList.toggle('show', nowOpen);
                document.body.style.overflow = nowOpen ? 'hidden' : '';
            }

            hamburger.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });
            overlay.addEventListener('click', function() {
                toggleSidebar(false);
            });
            // Close sidebar on any nav link click (mobile)
            document.querySelectorAll('.sidebar-nav a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        toggleSidebar(false);
                    }
                });
            });

            // ----- Mobile search toggle -----
            const searchToggle = document.getElementById('searchToggle');
            const searchForm = document.getElementById('searchForm');
            if (searchToggle && searchForm) {
                searchToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchForm.classList.toggle('expanded');
                    const input = searchForm.querySelector('.form-control');
                    if (searchForm.classList.contains('expanded')) {
                        setTimeout(function() { input.focus(); }, 200);
                    }
                });
                // Close search when clicking outside
                document.addEventListener('click', function(e) {
                    if (window.innerWidth < 576 && !searchForm.contains(e.target) && e.target !== searchToggle) {
                        searchForm.classList.remove('expanded');
                    }
                });
            }

            // ----- Dark mode toggle -----
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

            // ----- AJAX Search (only on Songs index) -----
            const searchFormAjax = document.getElementById('searchForm');
            const searchInputAjax = searchFormAjax?.querySelector('.form-control');
            const tableContainer = document.getElementById('tableContainer');

            if (searchFormAjax && searchInputAjax && tableContainer) {
                const form = searchFormAjax;
                const container = tableContainer;

                function fetchSearchResults() {
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData).toString();
                    const url = '{{ route("songs.index") }}?' + params;

                    const originalHtml = container.innerHTML;
                    container.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.text();
                    })
                    .then(html => {
                        container.innerHTML = html;
                        const newUrl = window.location.pathname + '?' + params;
                        window.history.pushState({ path: newUrl }, '', newUrl);
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        container.innerHTML = originalHtml || '<div class="alert alert-danger">Search failed. Please try again.</div>';
                    });
                }

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    fetchSearchResults();
                });

                let searchDebounce;
                searchInputAjax.addEventListener('input', function() {
                    clearTimeout(searchDebounce);
                    searchDebounce = setTimeout(fetchSearchResults, 300);
                });
            }

            // ----- SweetAlert2 Delete Confirmation -----
            document.querySelectorAll('form.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = this.dataset.confirm || 'Are you sure you want to delete this?';
                    Swal.fire({
                        title: 'Are you sure?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then(result => {
                        if (result.isConfirmed) this.submit();
                    });
                });
            });

            document.querySelectorAll('[data-confirm-delete]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    const message = this.dataset.confirmMessage || 'Are you sure?';
                    Swal.fire({
                        title: 'Are you sure?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then(result => {
                        if (result.isConfirmed) window.location.href = href;
                    });
                });
            });

            // ----- Toast success messages -----
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>
    @stack('js')
</body>
</html>
