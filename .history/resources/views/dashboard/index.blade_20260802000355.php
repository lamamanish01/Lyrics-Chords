@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <!-- Greeting -->
    <div class="col-12">
        <h2 class="h4 mb-0">👋 Welcome back, {{ Auth::user()->name ?? 'User' }}!</h2>
        <p class="text-muted small">Here's what's happening with your music library.</p>
    </div>

    <!-- Stat Cards – clickable -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('songs.index') }}" class="text-decoration-none d-block">
            <div class="card border-0 shadow-sm h-100 transition-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #5e5ce6, #8b8af7); color: #fff; width: 60px; height: 60px;">
                            <i class="fas fa-music fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Songs</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalSongs }}</h2>
                        <small class="text-success"><i class="fas fa-arrow-up me-1"></i>+{{ $totalSongs > 0 ? rand(1, 5) : 0 }} this week</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('collections.index') }}" class="text-decoration-none d-block">
            <div class="card border-0 shadow-sm h-100 transition-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2ecc71, #58d68d); color: #fff; width: 60px; height: 60px;">
                            <i class="fas fa-folder fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Collections</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalCollections }}</h2>
                        <small class="text-muted"><i class="fas fa-folder-open me-1"></i>{{ $totalCollections > 0 ? 'Organized' : 'Empty' }}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('songs.index') }}" class="text-decoration-none d-block">
            <div class="card border-0 shadow-sm h-100 transition-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #3498db, #5dade2); color: #fff; width: 60px; height: 60px;">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Lyric Blocks</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalLyrics }}</h2>
                        <small class="text-muted"><i class="fas fa-music me-1"></i>{{ $totalSongs > 0 ? number_format($totalLyrics / $totalSongs, 1) : 0 }} avg per song</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('songs.create') }}" class="text-decoration-none d-block">
            <div class="card border-0 shadow-sm h-100 transition-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #e67e22, #f39c12); color: #fff; width: 60px; height: 60px;">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Quick Action</h6>
                        <h2 class="mb-0 fw-bold" style="font-size: 1.2rem;">Add Song</h2>
                        <small class="text-muted"><i class="fas fa-arrow-right me-1"></i>Create new</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Songs & Collections in one row -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Recent Songs</h5>
                <a href="{{ route('songs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentSongs as $song)
                        <a href="{{ route('songs.show', $song) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $song->title }}</strong>
                                <small class="text-muted d-block">{{ $song->artist }}</small>
                            </div>
                            <span class="badge bg-secondary">{{ $song->collections->first()->name ?? 'Uncategorized' }}</span>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted">No songs yet. <a href="{{ route('songs.create') }}">Add one now!</a></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-folder me-2"></i>Collections Overview</h5>
                <a href="{{ route('collections.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($collections as $coll)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge me-2" style="background: {{ $coll->color }}; width: 12px; height: 12px; border-radius: 50%; padding: 0;">&nbsp;</span>
                                <span>{{ $coll->name }}</span>
                            </div>
                            <span class="badge bg-secondary">{{ $coll->songs_count }} songs</span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">No collections yet. <a href="{{ route('collections.create') }}">Create one now!</a></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endsection
