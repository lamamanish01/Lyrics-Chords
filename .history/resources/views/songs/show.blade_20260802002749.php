<!-- Inside the card-body, before the controls row -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <div>
        <button id="languageToggle" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-language me-1"></i> <span id="langLabel">नेपाली</span>
        </button>
    </div>
</div>

<!-- Lyrics Container -->
<div id="lyrics-container" style="font-size: 16px; line-height: 1.0;">
    @forelse($lyrics as $lyric)
        <div class="lyric-block mb-3 p-2 bg-light-subtle rounded-3 border-start border-4 border-primary">
            <!-- Romanized version -->
            <div class="lyric-content lang-roman" data-section="{{ $lyric->section }}">
                {!! \App\Helpers\ChordFormatter::toChordSheet($lyric->content) !!}
            </div>
            <!-- Nepali version (if exists) -->
            @if($lyric->content_nepali)
                <div class="lyric-content lang-nepali" style="display: none;" data-section="{{ $lyric->section }}">
                    {!! \App\Helpers\ChordFormatter::toChordSheet($lyric->content_nepali) !!}
                </div>
            @else
                <div class="lyric-content lang-nepali" style="display: none;" data-section="{{ $lyric->section }}">
                    <em class="text-muted">Nepali version not available.</em>
                </div>
            @endif
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
