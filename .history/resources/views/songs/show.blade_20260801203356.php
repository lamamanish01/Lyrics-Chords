@extends('layouts.app')

@section('title', $song->title)

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <h3 class="card-title mb-0">{{ $song->title }}</h3>
            <small class="text-muted">by {{ $song->artist }}</small>
            @foreach($song->collections as $coll)
                <span class="badge-collection ms-1" style="background: {{ $coll->color }}; color: #fff;">{{ $coll->name }}</span>
            @endforeach
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('lyrics.create', ['song_id' => $song->id]) }}" class="btn btn-primary btn-sm rounded-pill">
                <i class="fas fa-plus"></i> Add Lyric
            </a>
            <a href="{{ route('songs.edit', $song) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- ===== TOGGLE BUTTON ===== -->
        <div class="d-flex justify-content-center mb-2">
            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#controlsPanel" aria-expanded="true" aria-controls="controlsPanel">
                <i class="fas fa-sliders-h"></i> <span id="controlsToggleLabel">Hide Controls</span>
            </button>
        </div>

        <!-- ===== COLLAPSIBLE CONTROLS ===== -->
        <div class="collapse show" id="controlsPanel">
            <div class="controls-row p-3 mb-4 bg-light rounded-3">
                <!-- Row 1: Transpose + Font -->
                <div class="row g-2 mb-1">
                    <div class="col-12 col-md-6 d-flex align-items-center gap-1">
                        <span class="fw-bold me-1"><i class="fas fa-arrow-up-down"></i> Key:</span>
                        <a href="{{ request()->fullUrlWithQuery(['transpose' => $transpose - 1]) }}" class="btn btn-outline-secondary btn-sm" title="Transpose down">−1</a>
                        <a href="{{ request()->fullUrlWithQuery(['transpose' => $transpose + 1]) }}" class="btn btn-outline-secondary btn-sm" title="Transpose up">+1</a>
                        @if($transpose != 0)
                            <a href="{{ request()->fullUrlWithQuery(['transpose' => 0]) }}" class="btn btn-outline-danger btn-sm" title="Reset to original key">Reset ({{ $transpose > 0 ? '+' : '' }}{{ $transpose }})</a>
                        @else
                            <span class="badge bg-secondary">Original</span>
                        @endif
                    </div>
                    <div class="col-12 col-md-6 d-flex align-items-center gap-1">
                        <span class="fw-bold me-1"><i class="fas fa-font"></i> Size:</span>
                        <button id="fontDecrease" class="btn btn-outline-secondary btn-sm" title="Decrease font">A−</button>
                        <button id="fontIncrease" class="btn btn-outline-secondary btn-sm" title="Increase font">A+</button>
                        <button id="fontReset" class="btn btn-outline-secondary btn-sm" title="Reset font">Reset</button>
                    </div>
                </div>

                <!-- Row 2: Chord Toggle + Auto‑scroll Play -->
                <div class="row g-2 mb-1">
                    <div class="col-12 col-md-6 d-flex align-items-center gap-2">
                        <button id="chordToggle" class="btn btn-outline-info btn-sm" title="Show/Hide chords">
                            <i class="fas fa-music"></i> <span id="chordToggleLabel">Hide Chords</span>
                        </button>
                        <button id="scrollToggle" class="btn btn-success btn-sm" title="Play/pause auto‑scroll">
                            <i class="fas fa-play"></i> <span id="scrollStatusLabel">Play</span>
                        </button>
                    </div>
                    <div class="col-12 col-md-6 d-flex align-items-center gap-2">
                        <span class="fw-bold me-1">Speed:</span>
                        <input type="range" id="scrollSpeed" min="1" max="20" value="5" class="form-range" style="width: 120px;">
                        <span id="speedDisplay" class="badge bg-secondary">5</span>
                        <button id="scrollReset" class="btn btn-outline-secondary btn-sm" title="Scroll to top">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <span class="ms-auto text-muted small" id="scrollStatus">Paused</span>
                    </div>
                </div>

                <!-- Row 3: Copy Buttons + Keyboard hint -->
                <div class="row g-2">
                    <div class="col-12 col-md-6 d-flex gap-2">
                        <button id="copyLyricsWithChords" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-copy"></i> Copy with Chords
                        </button>
                        <button id="copyLyricsWithoutChords" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-copy"></i> Copy Lyrics Only
                        </button>
                    </div>
                    <div class="col-12 col-md-6 text-muted small text-md-end">
                        <i class="fas fa-info-circle"></i> Space: toggle scroll, ↑↓: speed
                    </div>
                </div>
            </div>
        </div>

        <!-- Lyrics Container -->
        <div id="lyrics-container" style="font-size: 16px; line-height: 1.0;">
            @forelse($lyrics as $lyric)
                <div class="lyric-block mb-3 p-2 bg-light-subtle rounded-3 border-start border-4 border-primary">
                    <div class="lyric-content">
                        {!! \App\Helpers\ChordFormatter::toChordSheet($lyric->content) !!}
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <a href="{{ route('lyrics.edit', $lyric) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        <form action="{{ route('lyrics.destroy', $lyric) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this lyric?')">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4">No lyrics yet. <a href="{{ route('lyrics.create', ['song_id' => $song->id]) }}">Add the first one!</a></p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('css')
    <style>
        .lyric-block {
            background: #f8fafc;
            transition: background 0.2s;
        }
        [data-bs-theme="dark"] .lyric-block {
            background: #1e2633;
        }
        .lyric-content {
            font-family: 'Courier New', monospace;
            white-space: pre;
            line-height: 1.0;
            margin: 0;
            padding: 0;
        }
        .chord-line {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.95em;
            letter-spacing: 0.5px;
            margin: 0;
            padding: 0;
            line-height: 1.0;
        }
        .lyric-line {
            margin: 0;
            padding: 0;
            line-height: 1.0;
        }
        .section-heading {
            font-weight: 700;
            font-size: 1.1em;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.15rem;
            margin: 0.5rem 0 0.15rem 0;
            color: var(--text-color);
        }
        #lyrics-container.hide-chords .chord-line {
            display: none;
        }
        .controls-row {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid var(--border-color);
        }
        [data-bs-theme="dark"] .controls-row {
            background: #1e2633;
            border-color: #2d3748;
        }
        @media (max-width: 768px) {
            .controls-row .row [class^="col-"] {
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 0.25rem;
            }
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('js/auto-scroll.js') }}"></script>
    <script src="{{ asset('js/lyrics-controls.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('lyrics-container');
            if (!container) return;

            // Toggle button text update
            const toggleBtn = document.querySelector('[data-bs-target="#controlsPanel"]');
            const toggleLabel = document.getElementById('controlsToggleLabel');
            if (toggleBtn && toggleLabel) {
                toggleBtn.addEventListener('click', function() {
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    toggleLabel.textContent = isExpanded ? 'Hide Controls' : 'Show Controls';
                });
            }

            // Copy with chords (preserves both chord and lyric lines)
            document.getElementById('copyLyricsWithChords')?.addEventListener('click', function() {
                const text = container.textContent;
                navigator.clipboard.writeText(text).then(() => {
                    const original = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(() => this.innerHTML = original, 2000);
                }).catch(() => alert('Could not copy.'));
            });

            // Copy lyrics only (remove chord lines)
            document.getElementById('copyLyricsWithoutChords')?.addEventListener('click', function() {
                const clone = container.cloneNode(true);
                clone.querySelectorAll('.chord-line').forEach(el => el.remove());
                const text = clone.textContent;
                navigator.clipboard.writeText(text).then(() => {
                    const original = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(() => this.innerHTML = original, 2000);
                }).catch(() => alert('Could not copy.'));
            });
        });
    </script>
@endpush
