@extends('layouts.app')

@section('title', 'Songs')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="fas fa-music text-primary-custom me-2"></i>All Songs</h1>
    <a href="{{ route('songs.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>New Song
    </a>
</div>

<div class="card">
    <div class="card-body">
        <!-- Filter row -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by title, artist, genre...">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4">
                <select name="collection" class="form-select">
                    <option value="">All Collections</option>
                    @foreach($collections as $coll)
                        <option value="{{ $coll->id }}" {{ request('collection') == $coll->id ? 'selected' : '' }}>
                            {{ $coll->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Filter</button>
                <a href="{{ route('songs.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Genre</th>
                        <th>Collections</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($songs as $song)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $song->title }}</strong></td>
                            <td>{{ $song->artist }}</td>
                            <td><span class="badge bg-secondary">{{ $song->genre ?? 'N/A' }}</span></td>
                            <td>
                                @foreach($song->collections as $coll)
                                    <span class="badge-collection" style="background: {{ $coll->color }}; color: #fff;">{{ $coll->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-end">
                                <a href="{{ route('songs.show', $song) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('songs.edit', $song) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('songs.destroy', $song) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this song?')" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No songs found. <a href="{{ route('songs.create') }}">Create one now</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $songs->links() }}
        </div>
    </div>
</div>
@endsection
