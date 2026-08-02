@extends('layouts.app')

@section('title', 'Add Lyric')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Add Lyric</h3></div>
    <div class="card-body">
        <form action="{{ route('lyrics.store') }}" method="POST" id="lyricForm">
            @csrf
            <div class="mb-3">
                <label for="song_id" class="form-label">Song</label>
                <select name="song_id" id="song_id" class="form-select" required>
                    @foreach($songs as $id => $title)
                        <option value="{{ $id }}" {{ old('song_id', $songId ?? '') == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">Section (optional)</label>
                <input type="text" name="section" id="section" class="form-control" value="{{ old('section') }}" placeholder="e.g., Verse, Chorus">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Lyrics & Chords</label>
                <textarea name="content" id="content" rows="10" class="form-control" required>{{ old('content') }}</textarea>
                <small class="text-muted">Use [Chord] syntax, e.g., [G] [C] [D]</small>
            </div>

            <!-- Preview Section -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label fw-bold">Preview</label>
                    <button type="button" id="previewBtn" class="btn btn-primary btn-sm rounded-pill">
                        <i class="fas fa-eye"></i> Preview
                    </button>
                </div>
                <div id="previewContainer" style="display: none; margin-top: 0.5rem;">
                    <div class="controls-row d-flex flex-wrap gap-2 p-2 mb-2 bg-light rounded-3">
                        <span class="fw-bold me-1">Font:</span>
                        <button class="preview-font-decrease btn btn-outline-secondary btn-sm">A−</button>
                        <button class="preview-font-increase btn btn-outline-secondary btn-sm">A+</button>
                        <button class="preview-font-reset btn btn-outline-secondary btn-sm">Reset</button>
                        <button class="preview-chord-toggle btn btn-outline-info btn-sm">
                            <i class="fas fa-music"></i> <span class="preview-chord-label">Hide Chords</span>
                        </button>
                    </div>
                    <div id="previewContent" class="p-2 bg-light-subtle rounded-3 border-start border-4 border-primary" style="font-family: 'Courier New', monospace; white-space: pre; line-height: 1.0; font-size: 16px; overflow-x: auto;">
                        <em class="text-muted">Click "Preview" to see the formatted lyrics.</em>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('songs.show', old('song_id', $songId ?? 0)) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

@push('css')
    <style>
        .preview-chord-line {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.95em;
            letter-spacing: 0.5px;
            margin: 0;
            padding: 0;
            line-height: 1.0;
        }
        .preview-lyric-line {
            margin: 0;
            padding: 0;
            line-height: 1.0;
        }
        .preview-section-heading {
            font-weight: 700;
            font-size: 1.1em;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.15rem;
            margin: 0.5rem 0 0.15rem 0;
            color: var(--text-color);
        }
        #previewContent.hide-chords .preview-chord-line {
            display: none;
        }
        .controls-row {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        [data-bs-theme="dark"] .controls-row {
            background: #1e2633;
            border-color: #2d3748;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('content');
            const previewBtn = document.getElementById('previewBtn');
            const previewContainer = document.getElementById('previewContainer');
            const previewContent = document.getElementById('previewContent');
            const previewFontSize = 16;
            let currentPreviewFont = previewFontSize;

            // Preview fetch
            previewBtn.addEventListener('click', function() {
                const text = content.value.trim();
                if (!text) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Empty',
                        text: 'Please enter some lyrics first.',
                        confirmButtonColor: '#6c5ce7'
                    });
                    return;
                }

                fetch('{{ route("lyrics.preview") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content: text })
                })
                .then(res => res.json())
                .then(data => {
                    previewContent.innerHTML = data.html;
                    // Re-apply any existing preview styles (chord toggle, font)
                    applyPreviewStyles();
                    previewContainer.style.display = 'block';
                })
                .catch(err => {
                    Swal.fire('Error', 'Could not preview. Please try again.', 'error');
                });
            });

            // Preview controls
            function applyPreviewStyles() {
                const container = previewContent;
                // Font
                container.style.fontSize = currentPreviewFont + 'px';
                // Chord toggle state
                const isHidden = container.classList.contains('hide-chords');
                const label = document.querySelector('.preview-chord-label');
                if (label) label.textContent = isHidden ? 'Show Chords' : 'Hide Chords';
            }

            // Font controls for preview
            document.querySelector('.preview-font-decrease').addEventListener('click', function() {
                currentPreviewFont = Math.max(10, currentPreviewFont - 2);
                previewContent.style.fontSize = currentPreviewFont + 'px';
            });
            document.querySelector('.preview-font-increase').addEventListener('click', function() {
                currentPreviewFont = Math.min(40, currentPreviewFont + 2);
                previewContent.style.fontSize = currentPreviewFont + 'px';
            });
            document.querySelector('.preview-font-reset').addEventListener('click', function() {
                currentPreviewFont = previewFontSize;
                previewContent.style.fontSize = currentPreviewFont + 'px';
            });

            // Chord toggle for preview
            document.querySelector('.preview-chord-toggle').addEventListener('click', function() {
                const container = previewContent;
                container.classList.toggle('hide-chords');
                const label = this.querySelector('.preview-chord-label');
                label.textContent = container.classList.contains('hide-chords') ? 'Show Chords' : 'Hide Chords';
            });

            // Auto-preview with debounce (optional, but we keep manual for now)
        });
    </script>
@endpush
