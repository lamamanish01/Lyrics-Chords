{{-- Wrap the entire lyrics section if 'view lyrics' permission is required --}}
@can('view lyrics')
    <div id="lyrics-container" style="font-size: 16px;">
        @forelse($lyrics as $lyric)
            <div class="lyric-block mb-3 p-2 bg-light-subtle rounded-3 border-start border-4 border-primary">
                <div class="lyric-content">
                    {!! \App\Helpers\ChordFormatter::toChordSheet($lyric->content) !!}
                </div>

                {{-- Only show action buttons if user has edit OR delete permissions --}}
                @if(auth()->user()->can('edit lyrics') || auth()->user()->can('delete lyrics'))
                    <div class="mt-2 d-flex gap-2">
                        @can('edit lyrics')
                            <a href="{{ route('lyrics.edit', $lyric) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        @endcan

                        @can('delete lyrics')
                            <form action="{{ route('lyrics.destroy', $lyric) }}" method="POST" class="delete-form" style="display:inline" data-confirm="Delete this lyric?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endcan
                    </div>
                @endif
            </div>
        @empty
            <p class="text-muted text-center py-4">
                No lyrics yet.
                @can('create lyrics')
                    <a href="{{ route('lyrics.create', ['song_id' => $song->id]) }}">Add the first one!</a>
                @endcan
            </p>
        @endforelse
    </div>
@else
    <p class="text-muted">You do not have permission to view lyrics.</p>
@endcan

{{-- You can place this CSS in your main stylesheet instead --}}
<style>
    .chord-sheet-pre {
        font-family: 'Courier New', monospace;
        font-size: 16px;
        line-height: 1.8;
        white-space: pre;
        margin: 0;
        padding: 0;
        background: transparent;
        border: none;
    }
    .section-heading {
        font-weight: bold;
        margin: 1em 0 0.5em 0;
        color: #2c3e50;
    }
    /* Ensure the container doesn't interfere */
    .lyric-content {
        white-space: normal; /* reset, as pre handles its own */
    }
</style>
