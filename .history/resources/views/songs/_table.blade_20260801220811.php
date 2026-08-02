<div class="table-responsive" id="songsTable">
    <table class="table table-hover align-middle text-nowrap">
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
                        <a href="{{ route('songs.show', $song) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('songs.edit', $song) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('songs.destroy', $song) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this song?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No songs found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $songs->links() }}
</div>
