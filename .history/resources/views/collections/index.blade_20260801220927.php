@extends('layouts.app')

@section('title', 'Collections')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-folder text-primary-custom me-2"></i>Collections</h1>
    <a href="{{ route('collections.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-1"></i> New Collection
    </a>
</div>

@if($collections->count())
    <div class="row g-3 g-md-4">
        @foreach($collections as $coll)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <!-- Colored top bar -->
                    <div class="card-top-bar" style="background: {{ $coll->color }}; height: 4px; border-radius: 12px 12px 0 0;"></div>
                    <div class="card-body text-center pt-3">
                        <!-- Icon circle -->
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                             style="width: 56px; height: 56px; background: {{ $coll->color }}20; color: {{ $coll->color }}; font-size: 24px; border: 2px solid {{ $coll->color }}40;">
                            <i class="fas fa-music"></i>
                        </div>
                        <h5 class="card-title mb-1">{{ $coll->name }}</h5>
                        <p class="card-text text-muted small mb-3">
                            <i class="fas fa-list-ul me-1"></i>{{ $coll->songs_count }} {{ Str::plural('song', $coll->songs_count) }}
                        </p>
                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                            <a href="{{ route('songs.index', ['collection' => $coll->id]) }}" class="btn btn-sm btn-primary px-3">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            <a href="{{ route('collections.edit', $coll) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('collections.destroy', $coll) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this collection?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <div class="empty-state-icon mb-4">
            <i class="fas fa-folder-open fa-4x text-muted"></i>
        </div>
        <h5 class="text-muted">No collections yet</h5>
        <p class="text-muted small">Create your first collection to organize your songs.</p>
        <a href="{{ route('collections.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
            <i class="fas fa-plus me-1"></i> Create Collection
        </a>
    </div>
@endif
@endsection

@push('css')
<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important;
    }
    .empty-state-icon {
        opacity: 0.6;
    }
    /* Mobile tweaks */
    @media (max-width: 576px) {
        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .card-body {
            padding: 0.75rem !important;
        }
        .card-body .rounded-circle {
            width: 44px !important;
            height: 44px !important;
            font-size: 18px !important;
        }
        .card-title {
            font-size: 0.95rem;
        }
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }
    }
</style>
@endpush
