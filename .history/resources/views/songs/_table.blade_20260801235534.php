<div class="table-responsive">
    <table class="table table-sm table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="min-width: 40px;">#</th>
                <th style="min-width: 120px;">Title</th>
                <th style="min-width: 120px;">Artist</th>
                <th style="min-width: 80px;">Genre</th>
                <th style="min-width: 100px;">Collections</th>
                <th style="min-width: 120px;" class="text-end">Actions</th>
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
                            <span class="badge-collection" style="background: {{ $coll->color }}; color: #fff; font-size: 0.65rem; padding: 0.2rem 0.4rem;">{{ $coll->name }}</span>
                        @endforeach
                    </td>
                    <td class="text-end" style="white-space: nowrap;">
                        <a href="{{ route('songs.show', $song) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('songs.edit', $song) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('songs.destroy', $song) }}" method="POST" class="delete-form" style="display:inline" data-confirm="Delete this song?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No songs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-3">
    {{ $songs->links() }}
</div>
