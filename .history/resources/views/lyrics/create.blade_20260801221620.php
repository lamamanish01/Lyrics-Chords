@extends('layouts.app')

@section('title', 'Add Lyric')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus-circle me-2"></i>Add Lyric</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('lyrics.store') }}" method="POST" id="lyricForm">
            @csrf

            <!-- Song selection -->
            <div class="mb-3">
                <label for="song_id" class="form-label">Song</label>
                <select name="song_id" id="song_id" class="form-select" required>
                    @foreach($songs as $id => $title)
                        <option value="{{ $id }}" {{ old('song_id', $songId ?? '') == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div class="mb-3">
                <label for="section" class="form-label">Section <span class="text-muted">(optional)</span></label>
                <input type="text" name="section" id="section" class="form-control" value="{{ old('section') }}" placeholder="e.g., Verse, Chorus, Bridge">
            </div>

            <!-- Lyrics & Chords – with helper toolbar -->
            <div class="mb-3">
                <label for="content" class="form-label">Lyrics & Chords</label>
                <div class="mb-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 insert-chord" data-chord="G">[G]</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 insert-chord" data-chord="C">[C]</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 insert-chord" data-chord="D">[D]</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 insert-chord" data-chord="Em">[Em]</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 insert-chord" data-chord="Am">[Am]</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 insert-chord" data-chord="F">[F]</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 insert-chord" data-chord="Bm">[Bm]</button>
                    <span class="mx-1 text-muted">|</span>
                    <button type="button" class="btn btn-outline-info btn-sm me-1 insert-section" data-section="Verse">[Verse]</button>
                    <button type="button" class="btn btn-outline-info btn-sm me-1 insert-section" data-section="Chorus">[Chorus]</button>
                    <button type="button" class="btn btn-outline-info btn-sm me-1 insert-section" data-section="Bridge">[Bridge]</button>
                    <button type="button" class="btn btn-outline-info btn-sm me-1 insert-section" data-section="Intro">[Intro]</button>
                    <button type="button" class="btn btn-outline-info btn-sm me-1 insert-section" data-section="Outro">[Outro]</button>
                </div>

                <textarea name="content" id="content" rows="8" class="form-control font-monospace" required>{{ old('content') }}</textarea>
                <small class="text-muted">Use <code>[Chord]</code> syntax for chords (e.g., <code>[G]</code>). Section headers like <code>[Verse]</code> will be displayed as headings.</small>
            </div>

            <!-- Preview toggle -->
            <div class="mb-3">
                <button type="button" class="btn btn-outline-primary btn-sm" id="togglePreview">
                    <i class="fas fa-eye me-1"></i> Preview
                </button>
            </div>

            <!-- Live preview container -->
            <div id="previewContainer" style="display: none;" class="mb-3">
                <label class="form-label fw-bold">Preview</label>
                <div id="previewContent" class="p-3 bg-light-subtle rounded-3 border-start border-4 border-primary" style="font-family: 'Courier New', monospace; white-space: pre; line-height: 1.0; font-size: 16px; overflow-x: auto;">
                    <em class="text-muted">Your lyrics will appear here formatted.</em>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            <a href="{{ route('songs.show', old('song_id', $songId ?? 0)) }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
        </form>
    </div>
</div>
@endsection

@push('css')
<style>
    .font-monospace {
        font-family: 'Courier New', monospace;
    }
    #previewContent .chord-line {
        color: var(--primary);
        font-weight: 600;
        font-size: 0.95em;
        letter-spacing: 0.5px;
        margin: 0;
        padding: 0;
        line-height: 1.0;
    }
    #previewContent .lyric-line {
        margin: 0;
        padding: 0;
        line-height: 1.0;
    }
    #previewContent .section-heading {
        font-weight: 700;
        font-size: 1.1em;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.15rem;
        margin: 0.5rem 0 0.15rem 0;
        color: var(--text-color);
    }
    .btn-group-helper .btn {
        margin-bottom: 0.25rem;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.getElementById('content');
        const previewContainer = document.getElementById('previewContainer');
        const previewContent = document.getElementById('previewContent');
        const togglePreviewBtn = document.getElementById('togglePreview');
        let previewDebounce;

        // ----- Insert chord/section at cursor -----
        function insertText(text) {
            const start = content.selectionStart;
            const end = content.selectionEnd;
            const before = content.value.substring(0, start);
            const after = content.value.substring(end);
            content.value = before + text + after;
            content.focus();
            content.selectionStart = content.selectionEnd = start + text.length;
            // Trigger preview update if visible
            if (previewContainer.style.display !== 'none') {
                updatePreview();
            }
        }

        document.querySelectorAll('.insert-chord').forEach(btn => {
            btn.addEventListener('click', function() {
                insertText(this.dataset.chord);
            });
        });

        document.querySelectorAll('.insert-section').forEach(btn => {
            btn.addEventListener('click', function() {
                insertText(this.dataset.section);
            });
        });

        // ----- Toggle preview -----
        togglePreviewBtn.addEventListener('click', function() {
            if (previewContainer.style.display === 'none') {
                previewContainer.style.display = 'block';
                this.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Hide Preview';
                updatePreview();
            } else {
                previewContainer.style.display = 'none';
                this.innerHTML = '<i class="fas fa-eye me-1"></i> Preview';
            }
        });

        // ----- Live preview (debounced) -----
        function updatePreview() {
            const text = content.value.trim();
            if (!text) {
                previewContent.innerHTML = '<em class="text-muted">Enter some lyrics to see the preview.</em>';
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
            })
            .catch(err => {
                previewContent.innerHTML = '<span class="text-danger">Error loading preview.</span>';
                console.error(err);
            });
        }

        // Auto-preview on input (only if preview is visible)
        content.addEventListener('input', function() {
            if (previewContainer.style.display !== 'none') {
                clearTimeout(previewDebounce);
                previewDebounce = setTimeout(updatePreview, 400);
            }
        });

        // If old value exists, and preview is visible, update
        if (old('content') && previewContainer.style.display !== 'none') {
            updatePreview();
        }
    });
</script>
@endpush
