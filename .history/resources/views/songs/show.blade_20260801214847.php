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
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <!-- "Show Controls" button – now on the right -->
            <button class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#controlsModal">
                <i class="fas fa-sliders-h me-1"></i> Controls
            </button>
            <a href="{{ route('lyrics.create', ['song_id' => $song->id]) }}" class="btn btn-primary btn-sm rounded-pill">
                <i class="fas fa-plus"></i> Add Lyric
            </a>
            <a href="{{ route('songs.edit', $song) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Lyrics Display -->
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

<!-- ====== CONTROLS MODAL (simplified & responsive) ====== -->
<div class="modal fade" id="controlsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-sliders-h me-2"></i>Lyrics Controls</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Controls grouped in a clean, stacked layout on mobile -->
                <div class="d-flex flex-column gap-3">
                    <!-- Transpose -->
                    <div>
                        <label class="fw-bold me-2"><i class="fas fa-arrow-up-down text-primary"></i> Key:</label>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ request()->fullUrlWithQuery(['transpose' => $transpose - 1]) }}" class="btn btn-outline-secondary btn-sm">−1</a>
                            <a href="{{ request()->fullUrlWithQuery(['transpose' => $transpose + 1]) }}" class="btn btn-outline-secondary btn-sm">+1</a>
                            @if($transpose != 0)
                                <a href="{{ request()->fullUrlWithQuery(['transpose' => 0]) }}" class="btn btn-outline-danger btn-sm">Reset ({{ $transpose > 0 ? '+' : '' }}{{ $transpose }})</a>
                            @else
                                <span class="badge bg-success">Original</span>
                            @endif
                        </div>
                    </div>

                    <!-- Font Size -->
                    <div>
                        <label class="fw-bold me-2"><i class="fas fa-font text-primary"></i> Size:</label>
                        <div class="d-flex flex-wrap align-items-center gap-1">
                            <button id="fontDecrease" class="btn btn-outline-secondary btn-sm">A−</button>
                            <button id="fontIncrease" class="btn btn-outline-secondary btn-sm">A+</button>
                            <button id="fontReset" class="btn btn-outline-secondary btn-sm">Reset</button>
                            <input type="range" id="fontSizeSlider" min="10" max="40" value="16" step="2" class="form-range" style="width: 120px;">
                            <span class="badge bg-secondary" id="fontSizeDisplay">16</span>
                        </div>
                    </div>

                    <!-- Chord Toggle & Auto‑scroll (with 0.5 step) -->
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <button id="chordToggle" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-music"></i> <span id="chordToggleLabel">Hide Chords</span>
                            </button>
                            <button id="scrollToggle" class="btn btn-success btn-sm">
                                <i class="fas fa-play"></i> <span id="scrollStatusLabel">Play</span>
                            </button>
                            <span class="fw-bold ms-1">Speed:</span>
                            <input type="range" id="scrollSpeed" min="0.5" max="20" step="0.5" value="5" class="form-range" style="width: 100px;">
                            <span id="speedDisplay" class="badge bg-secondary">5.0</span>
                            <button id="scrollReset" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <span class="text-muted small ms-auto" id="scrollStatus">Paused</span>
                        </div>
                    </div>

                    <!-- Copy Buttons -->
                    <div>
                        <div class="d-flex flex-wrap gap-2">
                            <button id="copyLyricsWithChords" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-copy"></i> Copy with Chords
                            </button>
                            <button id="copyLyricsWithoutChords" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-copy"></i> Copy Lyrics Only
                            </button>
                        </div>
                    </div>

                    <!-- Keyboard hints -->
                    <div class="text-muted small">
                        <i class="fas fa-keyboard"></i> <kbd>Space</kbd> toggle scroll · <kbd>↑</kbd><kbd>↓</kbd> speed
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
        .modal-content {
            background: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }
        .modal-header, .modal-footer {
            border-color: var(--border-color);
        }
        /* Mobile optimisation */
        @media (max-width: 576px) {
            .card-header .d-flex {
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: flex-end;
            }
            .card-header .d-flex .btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
            .modal-body .form-range {
                width: 80px !important;
            }
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/auto-scroll.js') }}"></script>
    <script src="{{ asset('js/lyrics-controls.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('lyrics-container');
            if (!container) return;

            // ----- Helper to extract plain text (excluding edit/delete buttons) -----
            function getLyricsText(includeChords) {
                const clone = container.cloneNode(true);
                clone.querySelectorAll('.lyric-block .mt-2.d-flex.gap-2').forEach(el => el.remove());
                if (!includeChords) {
                    clone.querySelectorAll('.chord-line').forEach(el => el.remove());
                }
                return clone.textContent;
            }

            // ----- Copy function with fallback -----
            function copyText(text, button) {
                const originalHtml = button.innerHTML;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(() => {
                        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                        setTimeout(() => button.innerHTML = originalHtml, 2000);
                    }).catch(() => fallbackCopy(text, button, originalHtml));
                } else {
                    fallbackCopy(text, button, originalHtml);
                }
            }

            function fallbackCopy(text, button, originalHtml) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(() => button.innerHTML = originalHtml, 2000);
                } catch (e) {
                    alert('Could not copy. Please select the text and copy manually.');
                }
                document.body.removeChild(textarea);
            }

            // ----- Copy handlers -----
            document.getElementById('copyLyricsWithChords')?.addEventListener('click', function(e) {
                const text = getLyricsText(true);
                copyText(text, this);
            });

            document.getElementById('copyLyricsWithoutChords')?.addEventListener('click', function(e) {
                const text = getLyricsText(false);
                copyText(text, this);
            });
        });
    </script>
@endpush
