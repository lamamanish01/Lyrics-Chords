<div id="lyrics-container" style="font-size: 16px; line-height: 1.0;">
    @forelse($lyrics as $lyric)
        <div class="lyric-block mb-3 p-2 bg-light-subtle rounded-3 border-start border-4 border-primary">
            <div class="lyric-content">
                {!! \App\Helpers\ChordFormatter::toChordSheet($lyric->content) !!}
            </div>
            <div class="mt-2 d-flex gap-2">
                <a href="{{ route('lyrics.edit', $lyric) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                <form action="{{ route('lyrics.destroy', $lyric) }}" method="POST" class="delete-form" style="display:inline" data-confirm="Delete this lyric?">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-muted text-center py-4">No lyrics yet. <a href="{{ route('lyrics.create', ['song_id' => $song->id]) }}">Add the first one!</a></p>
    @endforelse
</div>
