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

        <!-- ====== TOGGLE CONTROLS BUTTON ====== -->
        <div class="text-center mb-3">
            <button class="btn btn-outline-primary rounded-pill px-4" type="button" data-bs-toggle="collapse" data-bs-target="#controlsPanel" aria-expanded="true" aria-controls="controlsPanel">
                <i class="fas fa-sliders-h me-2"></i><span id="controlsToggleLabel">Hide Controls</span>
            </button>
        </div>

        <!-- ====== COLLAPSIBLE CONTROLS PANEL ====== -->
        <div class="collapse show" id="controlsPanel">
            <div class="controls-panel p-3 p-md-4 mb-4">

                <!-- Row 1: Transpose & Font -->
                <div class="row g-3 align-items-center">
                    <!-- Transpose -->
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center flex-wrap gap-1">
                            <span class="fw-bold me-1"><i class="fas fa-arrow-up-down text-primary"></i> Key:</span>
                            <a href="{{ request()->fullUrlWithQuery(['transpose' => $transpose - 1]) }}" class="btn btn-outline-secondary btn-sm" title="Transpose down">−1</a>
                            <a href="{{ request()->fullUrlWithQuery(['transpose' => $transpose + 1]) }}" class="btn btn-outline-secondary btn-sm" title="Transpose up">+1</a>
                            @if($transpose != 0)
                                <a href="{{ request()->fullUrlWithQuery(['transpose' => 0]) }}" class="btn btn-outline-danger btn-sm" title="Reset to original key">Reset ({{ $transpose > 0 ? '+' : '' }}{{ $transpose }})</a>
                            @else
                                <span class="badge bg-success">Original</span>
                            @endif
                        </div>
                    </div>
                    <!-- Font Size -->
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center flex-wrap gap-1">
                            <span class="fw-bold me-1"><i class="fas fa-font text-primary"></i> Size:</span>
                            <button id="fontDecrease" class="btn btn-outline-secondary btn-sm" title="Decrease font">A−</button>
                            <button id="fontIncrease" class="btn btn-outline-secondary btn-sm" title="Increase font">A+</button>
                            <button id="fontReset" class="btn btn-outline-secondary btn-sm" title="Reset font">Reset</button>
                        </div>
                    </div>
                </div>

                <hr class="my-2">

                <!-- Row 2: Chords Toggle & Auto-scroll Play -->
                <div class="row g-3 align-items-center">
                    <!-- Chord Toggle -->
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <button id="chordToggle" class="btn btn-outline-info btn-sm" title="Show/Hide chords">
                                <i class="fas fa-music"></i> <span id="chordToggleLabel">Hide Chords</span>
                            </button>
                            <button id="scrollToggle" class="btn btn-success btn-sm" title="Play/pause auto‑scroll">
                                <i class="fas fa-play"></i> <span id="scrollStatusLabel">Play</span>
                            </button>
                        </div>
                    </div>
                    <!-- Auto-scroll Speed & Top -->
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="fw-bold me-1">Speed:</span>
                            <input type="range" id="scrollSpeed" min="1" max="20" value="5" class="form-range" style="width: 120px;">
                            <span id="speedDisplay" class="badge bg-secondary">5</span>
                            <button id="scrollReset" class="btn btn-outline-secondary btn-sm" title="Scroll to top">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <span class="text-muted small ms-auto" id="scrollStatus">Paused</span>
                        </div>
                    </div>
                </div>

                <hr class="my-2">

                <!-- Row 3: Copy Buttons & Keyboard Hints -->
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-7">
                        <div class="d-flex flex-wrap gap-2">
                            <button id="copyLyricsWithChords" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-copy"></i> Copy with Chords
                            </button>
                            <button id="copyLyricsWithoutChords" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-copy"></i> Copy Lyrics Only
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 text-muted small text-md-end">
                        <i class="fas fa-keyboard"></i> <kbd>Space</kbd> toggle scroll · <kbd>↑</kbd><kbd>↓</kbd> speed
                    </div>
                </div>

            </div> <!-- /.controls-panel -->
        </div> <!-- /.collapse -->

        <!-- ====== LYRICS DISPLAY ====== -->
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

        /* --- Controls Panel --- */
        .controls-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }
        [data-bs-theme="dark"] .controls-panel {
            background: rgba(30, 38, 51, 0.85);
            border-color: rgba(255, 255, 255, 0.05);
        }
        .controls-panel hr {
            border-color: var(--border-color);
            opacity: 0.4;
        }
        .controls-panel .form-range {
            cursor: pointer;
        }
        .controls-panel .btn {
            transition: all 0.15s;
        }
        .controls-panel .btn:hover {
            transform: translateY(-1px);
        }
        /* Toggle button */
        .btn-outline-primary.rounded-pill {
            border-width: 2px;
            font-weight: 500;
            background: transparent;
            transition: all 0.3s;
        }
        .btn-outline-primary.rounded-pill:hover {
            background: var(--primary);
            color: #fff;
            transform: scale(1.02);
        }
        .btn-outline-primary.rounded-pill i {
            transition: transform 0.3s;
        }
        .btn-outline-primary.rounded-pill[aria-expanded="false"] i {
            transform: rotate(90deg);
        }

        @media (max-width: 768px) {
            .controls-panel {
                padding: 1rem !important;
            }
            .controls-panel .row > [class^="col-"] {
                margin-bottom: 0.5rem;
                text-align: center;
                justify-content: center !important;
            }
            .controls-panel .row > [class^="col-"]:last-child {
                margin-bottom: 0;
            }
            .controls-panel .text-md-end {
                text-align: center !important;
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
