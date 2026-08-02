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
                        <!-- Icon circle with soft background -->
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                             style="width: 56px; height: 56px; background: {{ $coll->color }}20; color: {{ $coll->color }}; font-size: 24px; border: 2px solid {{ $coll->color }}40;">
                            <i class="fas fa-music"></i>
                        </div>
                        <h5 class="card-title mb-1">{{ $coll->name }}</h5>
                        <p class="card-text text-muted small mb-3">
                            <i class="fas fa-list-ul me-1"></i>{{ $coll->songs_count }} {{ Str::plural('song', $coll->songs_count) }}
                        </p>
                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                            <!-- View Songs – compact but readable -->
                            <a href="{{ route('songs.index', ['collection' => $coll->id]) }}" class="btn btn-sm btn-primary px-3 btn-action">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            <!-- Edit – icon only with tooltip -->
                            <a href="{{ route('collections.edit', $coll) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete – icon only with tooltip -->
                            <form action="{{ route('collections.destroy', $coll) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-action" onclick="return confirm('Delete this collection?')" title="Delete">
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
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border-radius: 12px;
        overflow: hidden;
        background: var(--card-bg);
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important;
    }
    .btn-action {
        transition: all 0.2s ease;
        border-radius: 8px;
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        min-width: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.2rem;
    }
    .btn-action i {
        font-size: 0.8rem;
    }
    /* Primary action hover – subtle glow */
    .btn-action.btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(94, 92, 230, 0.25);
    }
    .btn-action.btn-outline-secondary:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }
    .btn-action.btn-outline-danger:hover {
        background: #f8d7da;
        border-color: #dc3545;
        color: #dc3545;
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
            font-size: 0.9rem;
        }
        .btn-action {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            min-width: 28px;
        }
        .btn-action i {
            font-size: 0.7rem;
        }
        .btn-action.btn-primary {
            padding-left: 0.6rem;
            padding-right: 0.6rem;
        }
    }
</style>
@endpush
