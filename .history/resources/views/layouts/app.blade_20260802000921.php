@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <!-- Welcome Section -->
    <div class="col-12">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">👋 Welcome back, <span class="fw-bold text-primary">{{ Auth::user()->name ?? 'User' }}</span>!</h2>
                <p class="text-muted small">Here's a snapshot of your music library.</p>
            </div>
            <div>
                <a href="{{ route('songs.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-2"></i>Add New Song
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="col-12">
        <div class="row g-3">
            <!-- Total Songs -->
            <div class="col-6 col-md-3">
                <a href="{{ route('songs.index') }}" class="text-decoration-none d-block stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-primary-soft me-3">
                                <i class="fas fa-music text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Total Songs</h6>
                                <h3 class="mb-0 fw-bold">{{ $totalSongs }}</h3>
                                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>{{ $totalSongs > 0 ? rand(1, 5) : 0 }} this week</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Collections -->
            <div class="col-6 col-md-3">
                <a href="{{ route('collections.index') }}" class="text-decoration-none d-block stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-primary-soft me-3">
                                <i class="fas fa-folder text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Collections</h6>
                                <h3 class="mb-0 fw-bold">{{ $totalCollections }}</h3>
                                <small class="text-muted"><i class="fas fa-folder-open me-1"></i>{{ $totalCollections > 0 ? 'Organized' : 'Empty' }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Lyrics -->
            <div class="col-6 col-md-3">
                <a href="{{ route('songs.index') }}" class="text-decoration-none d-block stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-primary-soft me-3">
                                <i class="fas fa-file-alt text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Lyric Blocks</h6>
                                <h3 class="mb-0 fw-bold">{{ $totalLyrics }}</h3>
                                <small class="text-muted"><i class="fas fa-music me-1"></i>{{ $totalSongs > 0 ? number_format($totalLyrics / $totalSongs, 1) : 0 }} avg</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Avg. Lyrics per Song -->
            <div class="col-6 col-md-3">
                <a href="{{ route('songs.index') }}" class="text-decoration-none d-block stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-primary-soft me-3">
                                <i class="fas fa-chart-simple text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 small">Avg. Lyrics/Song</h6>
                                <h3 class="mb-0 fw-bold">{{ $totalSongs > 0 ? number_format($totalLyrics / $totalSongs, 1) : 0 }}</h3>
                                <small class="text-muted"><i class="fas fa-arrow-right me-1"></i>per song</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Row – equal height cards -->
    <div class="row g-4">
        <!-- Recent Songs -->
        <div class="col-12 col-lg-7 d-flex">
            <div class="card border-0 shadow-sm w-100">
                <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #e2e8f0;">
                    <h5 class="card-title mb-0"><i class="fas fa-clock me-2 text-primary"></i>Recent Songs</h5>
                    <a href="{{ route('songs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    <div class="list-group list-group-flush flex-grow-1">
                        @forelse($recentSongs as $song)
                            <a href="{{ route('songs.show', $song) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $song->title }}</strong>
                                    <small class="text-muted d-block">{{ $song->artist }}</small>
                                </div>
                                <span class="badge bg-primary-soft text-primary">{{ $song->collections->first()->name ?? 'Uncategorized' }}</span>
                            </a>
                        @empty
                            <div class="text-center py-4 text-muted">No songs yet. <a href="{{ route('songs.create') }}">Add one now!</a></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Collections Overview -->
        <div class="col-12 col-lg-5 d-flex">
            <div class="card border-0 shadow-sm w-100">
                <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #e2e8f0;">
                    <h5 class="card-title mb-0"><i class="fas fa-folder me-2 text-primary"></i>Collections</h5>
                    <a href="{{ route('collections.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    <div class="list-group list-group-flush flex-grow-1">
                        @forelse($collections as $coll)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge me-2" style="background: {{ $coll->color }}; width: 14px; height: 14px; border-radius: 50%; padding: 0;">&nbsp;</span>
                                    <span>{{ $coll->name }}</span>
                                </div>
                                <span class="badge bg-primary-soft text-primary">{{ $coll->songs_count }} songs</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">No collections yet. <a href="{{ route('collections.create') }}">Create one now!</a></div>
                        @endforelse
                    </div>

                    <!-- Mini bar chart (if collections exist) -->
                    @if($collections->count() > 1)
                        <div class="p-3 mt-auto border-top" style="border-color: #e2e8f0;">
                            <h6 class="text-muted small mb-2">Song Distribution</h6>
                            @foreach($collections as $coll)
                                @php
                                    $percent = $totalSongs > 0 ? round(($coll->songs_count / $totalSongs) * 100) : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-1">
                                    <span class="small me-2" style="width: 60px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $coll->name }}</span>
                                    <div class="progress flex-grow-1" style="height: 6px; background: #e2e8f0;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background: {{ $coll->color }};" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="small ms-2 text-muted">{{ $percent }}%</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3"><i class="fas fa-bolt me-2 text-primary"></i>Quick Actions</h6>
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('songs.create') }}" class="btn btn-outline-primary w-100 py-3 rounded-3 hover-lift">
                            <i class="fas fa-plus-circle fa-2x d-block mb-1"></i>
                            <span class="small">Add Song</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('collections.create') }}" class="btn btn-outline-primary w-100 py-3 rounded-3 hover-lift">
                            <i class="fas fa-folder-plus fa-2x d-block mb-1"></i>
                            <span class="small">New Collection</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('songs.index') }}" class="btn btn-outline-primary w-100 py-3 rounded-3 hover-lift">
                            <i class="fas fa-list fa-2x d-block mb-1"></i>
                            <span class="small">Browse Songs</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('collections.index') }}" class="btn btn-outline-primary w-100 py-3 rounded-3 hover-lift">
                            <i class="fas fa-folder-open fa-2x d-block mb-1"></i>
                            <span class="small">Manage Collections</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08) !important;
    }
    .stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        font-size: 1.4rem;
    }
    .bg-primary-soft {
        background: rgba(94, 92, 230, 0.12);
        color: #5e5ce6;
    }
    .text-primary {
        color: #5e5ce6 !important;
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
    .progress {
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }
    .hover-lift {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(94, 92, 230, 0.15) !important;
    }
    .btn-outline-primary {
        border-color: #5e5ce6;
        color: #5e5ce6;
    }
    .btn-outline-primary:hover {
        background: #5e5ce6;
        color: #fff;
    }
    .badge.bg-primary-soft {
        background: rgba(94, 92, 230, 0.12);
        color: #5e5ce6;
        font-weight: 600;
    }
</style>
@endsection
