@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3" style="background: rgba(94, 92, 230, 0.15); color: #5e5ce6;">
                            <i class="fas fa-music fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Songs</h6>
                        <h2 class="mb-0">{{ $totalSongs }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71;">
                            <i class="fas fa-folder fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Collections</h6>
                        <h2 class="mb-0">{{ $totalCollections }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3" style="background: rgba(52, 152, 219, 0.15); color: #3498db;">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Lyric Blocks</h6>
                        <h2 class="mb-0">{{ $totalLyrics }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-3" style="background: rgba(231, 76, 60, 0.15); color: #e74c3c;">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Avg. Lyrics/Song</h6>
                        <h2 class="mb-0">{{ $totalSongs > 0 ? number_format($totalLyrics / $totalSongs, 1) : 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Songs -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
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

    <!-- Collections Overview -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-folder me-2"></i>Collections</h5>
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

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('songs.create') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                            Add Song
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('collections.create') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-folder-plus fa-2x d-block mb-2"></i>
                            New Collection
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('songs.index') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-list fa-2x d-block mb-2"></i>
                            Browse Songs
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-folder-open fa-2x d-block mb-2"></i>
                            Manage Collections
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
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
@endpush
