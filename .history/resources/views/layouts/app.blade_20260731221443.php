<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- CoreUI CSS (compiled) -->
    <link rel="stylesheet" href="{{ asset('css/coreui.css') }}">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('css')
</head>
<body class="c-app">
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed" id="sidebar">
        <div class="c-sidebar-brand">
            <span>{{ config('app.name') }}</span>
        </div>
        <ul class="c-sidebar-nav">
            <li class="c-sidebar-nav-item">
                <a href="{{ route('songs.index') }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-music"></i> Songs
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a href="{{ route('collections.index') }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-folder"></i> Collections
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a href="{{ route('logout') }}" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="c-sidebar-nav-icon fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </li>
        </ul>
    </div>
    <div class="c-wrapper c-fixed-components">
        <header class="c-header c-header-light c-header-fixed">
            <button class="c-header-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#headerNav" aria-controls="headerNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="c-header-nav ms-auto">
                <span class="c-header-nav-link">{{ Auth::user()->name ?? 'Guest' }}</span>
            </div>
        </header>
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
        <footer class="c-footer">
            <div>&copy; {{ date('Y') }} {{ config('app.name') }}</div>
        </footer>
    </div>

    <!-- CoreUI JS Bundle (requires Bootstrap & Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/js/coreui.bundle.min.js"></script>
    <!-- Your custom JS -->
    @stack('js')
</body>
</html>
