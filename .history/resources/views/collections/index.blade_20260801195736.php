@extends('layouts.app')

@section('title', 'Collections')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="fas fa-folder text-primary-custom me-2"></i>Collections</h1>
    <a href="{{ route('collections.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus"></i> New Collection
    </a>
</div>

<div class="row g-4">
    @forelse($collections as $coll)
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm hover-shadow transition">
                <div class="card-body text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width: 64px; height: 64px; background: {{ $coll->color }}; color: #fff; font-size: 28px;">
                        <i class="fas fa-music"></i>
                    </div>
                    <h5 class="card-title">{{ $coll->name }}</h5>
                    <p class="card-text text-muted small">{{ $coll->songs_count }} songs</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        {{-- View Songs button – filters by this collection --}}
                        <a href="{{ route('songs.index', ['collection' => $coll->id]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> View Songs
                        </a>
                        <a href="{{ route('collections.edit', $coll) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('collections.destroy', $coll) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this collection?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
            No collections yet. <a href="{{ route('collections.create') }}">Create one now</a>.
        </div>
    @endforelse
</div>

{{-- Optional: extra style for hover effect --}}
@push('css')
<style>
    .hover-shadow {
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .hover-shadow:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
        transform: translateY(-2px);
    }
</style>
@endpush
@endsection
