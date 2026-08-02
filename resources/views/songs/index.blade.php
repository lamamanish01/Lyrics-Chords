@extends('layouts.app')

@section('title', 'Songs')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="fas fa-music text-primary-custom me-2"></i>All Songs</h1>
    <a href="{{ route('songs.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="fas fa-plus me-1"></i>New Song
    </a>
</div>

<div class="card">
    <div class="card-body p-2 p-md-3">
        <!-- Filter form (AJAX) – fully responsive -->
        <form method="GET" class="row g-2 mb-3" id="filterForm">
            <!-- Search input – full width on mobile -->
            <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by title, artist, genre...">
                    <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <!-- Collection dropdown – full width on mobile -->
            <div class="col-12 col-md-4">
                <select name="collection" id="collectionFilter" class="form-select form-select-sm">
                    <option value="">All Collections</option>
                    @foreach($collections as $coll)
                        <option value="{{ $coll->id }}" {{ request('collection') == $coll->id ? 'selected' : '' }}>{{ $coll->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Buttons – stack on mobile, inline on desktop -->
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-fill" type="submit">Filter</button>
                <a href="{{ route('songs.index') }}" class="btn btn-outline-secondary btn-sm flex-fill" id="resetFilter">Reset</a>
            </div>
        </form>

        <!-- Table container -->
        <div id="tableContainer">
            @include('songs._table', ['songs' => $songs])
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const tableContainer = document.getElementById('tableContainer');

        function fetchFilteredData() {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const url = '{{ route("songs.index") }}?' + params;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                tableContainer.innerHTML = html;
            })
            .catch(err => console.error('Filter error:', err));
        }

        // Submit form via AJAX
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchFilteredData();
        });

        // Debounce on search input (300ms)
        let debounceTimer;
        document.getElementById('searchInput')?.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchFilteredData(), 300);
        });

        // Collection filter change
        document.getElementById('collectionFilter')?.addEventListener('change', function() {
            fetchFilteredData();
        });

        // Reset button
        document.getElementById('resetFilter')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('searchInput').value = '';
            document.getElementById('collectionFilter').value = '';
            fetchFilteredData();
        });
    });
</script>
@endpush
