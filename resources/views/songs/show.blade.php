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
            <button id="languageToggle" class="btn btn-outline-secondary btn-sm" title="Toggle language">
                <i class="fas fa-language me-1"></i> <span id="langLabel">नेपाली</span>
            </button>
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
        <div id="lyrics-container" style="line-height: 1.0;">
            @forelse($lyrics as $lyric)
                <div class="lyric-block mb-3 p-2 bg-light-subtle rounded-3 border-start border-4 border-primary">
                    <div class="lyric-content lang-roman" data-section="{{ $lyric->section }}">
                        {!! \App\Helpers\ChordFormatter::toChordSheet($lyric->content) !!}
                    </div>
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
                </div>
            @empty
                <p class="text-muted text-center py-4">No lyrics yet. <a href="{{ route('lyrics.create', ['song_id' => $song->id]) }}">Add the first one!</a></p>
            @endforelse
        </div>
    </div>
</div>

<!-- Controls Modal -->
<div class="modal fade" id="controlsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-sliders-h me-2"></i>Lyrics Controls</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <label class="fw-bold me-2"><i class="fas fa-arrow-up-down text-primary"></i> Key:</label>
                        <div class="d-flex flex-wrap gap-1" id="transposeControls">
                            <button id="transposeMinus" class="btn btn-outline-secondary btn-sm transpose-btn" data-transpose="{{ $transpose - 1 }}">−1</button>
                            <button id="transposePlus" class="btn btn-outline-secondary btn-sm transpose-btn" data-transpose="{{ $transpose + 1 }}">+1</button>
                            @if($transpose != 0)
                                <button class="btn btn-outline-danger btn-sm transpose-btn" data-transpose="0">Reset ({{ $transpose > 0 ? '+' : '' }}{{ $transpose }})</button>
                            @else
                                <span class="badge bg-success" id="transposeDisplay">Original</span>
                            @endif
                        </div>
                    </div>
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
        /* ----- Remove card padding ----- */
        .card,
        .card-body,
        .card-header {
            padding: 0 !important;
        }

        .lyric-block {
            background: #f8fafc;
            transition: background 0.2s;
            overflow-x: auto;            /* Enable horizontal scroll */
            -webkit-overflow-scrolling: touch; /* smooth scroll on iOS */
        }
        [data-bs-theme="dark"] .lyric-block {
            background: #1e2633;
        }

        #lyrics-container {
            font-size: 16px;
        }

        /* ----- Enforce no wrapping on chord/lyric lines ----- */
        .lyric-content {
            display: flex;
            flex-direction: column;
            gap: 0;
            line-height: 1;
            margin: 0;
            padding: 0;
            font-family: 'SF Mono', 'Consolas', 'Menlo', 'Liberation Mono', 'Courier New', monospace;
            white-space: pre !important;   /* force no wrap */
            overflow-wrap: normal !important;
            word-wrap: normal !important;
            word-break: normal !important;
            overflow-x: auto;              /* additional scroll container */
        }

        .lyric-content .chord-line,
        .lyric-content .lyric-line {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1 !important;
            white-space: pre !important;   /* each line also no wrap */
            font-size: 1em;
            letter-spacing: 0;
            overflow-wrap: normal !important;
            word-wrap: normal !important;
            word-break: normal !important;
        }

        .lyric-content .chord-line {
            color: var(--primary);
            font-weight: 600;
        }

        .section-heading {
            font-weight: 700;
            font-size: 1.1em;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.15rem;
            margin: 0.5rem 0 0.15rem 0;
            color: var(--text-color);
            white-space: normal;           /* headings can wrap */
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

        /* ----- Mobile: base sizes (overridable by JS) ----- */
        @media (max-width: 576px) {
            #lyrics-container {
                font-size: 14px;
            }
            .modal-body .form-range {
                width: 100% !important;
                flex: 1;
            }
            #transposeControls .btn {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }
            .modal-dialog {
                margin: 0.5rem;
            }
        }

        @media (max-width: 400px) {
            #lyrics-container {
                font-size: 12px;
            }
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('js/auto-scroll.js') }}"></script>
    <script src="{{ asset('js/lyrics-controls.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ----- Language Toggle -----
            const langToggle = document.getElementById('languageToggle');
            const langLabel = document.getElementById('langLabel');
            const romanBlocks = document.querySelectorAll('.lang-roman');
            const nepaliBlocks = document.querySelectorAll('.lang-nepali');
            let showNepali = false;

            if (langToggle) {
                langToggle.addEventListener('click', function() {
                    showNepali = !showNepali;
                    romanBlocks.forEach(el => el.style.display = showNepali ? 'none' : '');
                    nepaliBlocks.forEach(el => el.style.display = showNepali ? '' : 'none');
                    langLabel.textContent = showNepali ? 'Roman' : 'नेपाली';
                });
            }

            // ----- AJAX Transpose -----
            const csrfToken = '{{ csrf_token() }}';
            const transposeUrl = '{{ route("songs.transpose.ajax", $song) }}';
            const transposeControls = document.getElementById('transposeControls');

            function getContainer() {
                return document.getElementById('lyrics-container');
            }

            function applyStoredSettings() {
                const container = getContainer();
                if (!container) return;

                const storedSize = localStorage.getItem('lyricsFontSize');
                if (storedSize !== null) {
                    container.style.fontSize = storedSize + 'px';
                    const slider = document.getElementById('fontSizeSlider');
                    const display = document.getElementById('fontSizeDisplay');
                    if (slider) slider.value = storedSize;
                    if (display) display.textContent = storedSize;
                } else {
                    container.style.fontSize = '';
                    const slider = document.getElementById('fontSizeSlider');
                    const display = document.getElementById('fontSizeDisplay');
                    const computed = window.getComputedStyle(container).fontSize;
                    const size = parseFloat(computed) || 16;
                    if (slider) slider.value = size;
                    if (display) display.textContent = Math.round(size);
                }

                const chordsVisible = localStorage.getItem('chordsVisible') !== 'false';
                container.classList.toggle('hide-chords', !chordsVisible);
            }

            function updateTransposeDisplay(transpose) {
                const displaySpan = document.getElementById('transposeDisplay');
                if (displaySpan) {
                    if (transpose == 0) {
                        displaySpan.outerHTML = '<span class="badge bg-success" id="transposeDisplay">Original</span>';
                    } else {
                        displaySpan.outerHTML = `<span class="badge bg-info" id="transposeDisplay">${transpose > 0 ? '+' : ''}${transpose}</span>`;
                    }
                }
                const minusBtn = document.getElementById('transposeMinus');
                const plusBtn = document.getElementById('transposePlus');
                if (minusBtn) minusBtn.dataset.transpose = transpose - 1;
                if (plusBtn) plusBtn.dataset.transpose = transpose + 1;

                const existingReset = transposeControls.querySelector('.btn-outline-danger');
                if (transpose == 0) {
                    if (existingReset) existingReset.remove();
                } else {
                    if (!existingReset) {
                        const btn = document.createElement('button');
                        btn.className = 'btn btn-outline-danger btn-sm transpose-btn';
                        btn.dataset.transpose = '0';
                        btn.textContent = `Reset (${transpose > 0 ? '+' : ''}${transpose})`;
                        transposeControls.appendChild(btn);
                    } else {
                        existingReset.textContent = `Reset (${transpose > 0 ? '+' : ''}${transpose})`;
                        existingReset.dataset.transpose = '0';
                    }
                }
            }

            function performTranspose(transpose) {
                fetch(transposeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ transpose: transpose })
                })
                .then(res => res.json())
                .then(data => {
                    const oldContainer = getContainer();
                    if (oldContainer) {
                        const temp = document.createElement('div');
                        temp.innerHTML = data.html;
                        const newContainer = temp.querySelector('#lyrics-container');
                        if (newContainer) {
                            oldContainer.parentNode.replaceChild(newContainer, oldContainer);
                            applyStoredSettings();
                            updateTransposeDisplay(transpose);
                            const romanBlocksNew = document.querySelectorAll('.lang-roman');
                            const nepaliBlocksNew = document.querySelectorAll('.lang-nepali');
                            if (showNepali) {
                                romanBlocksNew.forEach(el => el.style.display = 'none');
                                nepaliBlocksNew.forEach(el => el.style.display = '');
                            } else {
                                romanBlocksNew.forEach(el => el.style.display = '');
                                nepaliBlocksNew.forEach(el => el.style.display = 'none');
                            }
                        }
                    }
                })
                .catch(err => console.error('Transpose error:', err));
            }

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.transpose-btn');
                if (btn) {
                    e.preventDefault();
                    const transpose = parseInt(btn.dataset.transpose);
                    if (!isNaN(transpose)) {
                        performTranspose(transpose);
                    }
                }
            });

            applyStoredSettings();

            // ----- Font size controls -----
            const slider = document.getElementById('fontSizeSlider');
            const display = document.getElementById('fontSizeDisplay');
            const decreaseBtn = document.getElementById('fontDecrease');
            const increaseBtn = document.getElementById('fontIncrease');
            const resetBtn = document.getElementById('fontReset');

            function setFontSize(size) {
                const container = getContainer();
                if (!container) return;
                size = Math.min(40, Math.max(10, size));
                container.style.fontSize = size + 'px';
                if (slider) slider.value = size;
                if (display) display.textContent = size;
                localStorage.setItem('lyricsFontSize', size);
            }

            if (slider) {
                slider.addEventListener('input', function() {
                    setFontSize(parseFloat(this.value));
                });
            }

            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', function() {
                    const current = parseFloat(getContainer()?.style.fontSize) || 16;
                    setFontSize(current - 2);
                });
            }

            if (increaseBtn) {
                increaseBtn.addEventListener('click', function() {
                    const current = parseFloat(getContainer()?.style.fontSize) || 16;
                    setFontSize(current + 2);
                });
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    localStorage.removeItem('lyricsFontSize');
                    const container = getContainer();
                    if (container) {
                        container.style.fontSize = '';
                    }
                    const computed = window.getComputedStyle(container).fontSize;
                    const size = parseFloat(computed) || 16;
                    if (slider) slider.value = size;
                    if (display) display.textContent = Math.round(size);
                });
            }
        });
    </script>
@endpush
