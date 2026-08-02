@extends('layouts.app')

@section('title', 'Add Lyric')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-plus-circle me-2"></i>Add Lyric</h3>
        <span class="badge bg-primary">New</span>
    </div>
    <div class="card-body">
        <form action="{{ route('lyrics.store') }}" method="POST" id="lyricForm">
            @csrf

            <div class="row g-3">
                <!-- Left: Form -->
                <div class="col-lg-6">
                    <!-- Song -->
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
                        <input type="text" name="section" id="section" class="form-control" value="{{ old('section') }}" placeholder="e.g., Verse, Chorus">
                    </div>

                    <!-- Chord helper buttons -->
                    <div class="mb-2">
                        <label class="form-label small text-muted">Quick insert chords:</label>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-chord" data-chord="[G]">[G]</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-chord" data-chord="[C]">[C]</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-chord" data-chord="[D]">[D]</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-chord" data-chord="[Em]">[Em]</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-chord" data-chord="[Am]">[Am]</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-chord" data-chord="[F]">[F]</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-chord" data-chord="[Bm]">[Bm]</button>
                            <span class="mx-1 text-muted">|</span>
                            <button type="button" class="btn btn-outline-info btn-sm insert-section" data-section="[Verse]">[Verse]</button>
                            <button type="button" class="btn btn-outline-info btn-sm insert-section" data-section="[Chorus]">[Chorus]</button>
                            <button type="button" class="btn btn-outline-info btn-sm insert-section" data-section="[Bridge]">[Bridge]</button>
                            <button type="button" class="btn btn-outline-info btn-sm insert-section" data-section="[Intro]">[Intro]</button>
                            <button type="button" class="btn btn-outline-info btn-sm insert-section" data-section="[Outro]">[Outro]</button>
                        </div>
                    </div>

                    <!-- Formatting toolbar -->
                    <div class="mb-2">
                        <label class="form-label small text-muted">Formatting:</label>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-format" data-format="[b]" data-close="[/b]"><strong>B</strong></button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-format" data-format="[i]" data-close="[/i]"><em>I</em></button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-format" data-format="[u]" data-close="[/u]"><u>U</u></button>
                            <span class="mx-1 text-muted">|</span>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-format" data-format="[center]" data-close="[/center]">Center</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-format" data-format="[left]" data-close="[/left]">Left</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm insert-format" data-format="[right]" data-close="[/right]">Right</button>
                        </div>
                    </div>

                    <!-- Lyrics textarea -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Lyrics & Chords</label>
                        <textarea name="content" id="content" rows="10" class="form-control font-monospace" required>{{ old('content') }}</textarea>
                        <small class="text-muted">
                            Use <code>[Chord]</code> for chords. Section headers like <code>[Verse]</code> become bold headings.<br>
                            Formatting: <code>[b]bold[/b]</code> <code>[i]italic[/i]</code> <code>[u]underline[/u]</code> <code>[center]center[/center]</code> <code>[left]left[/left]</code> <code>[right]right[/right]</code>
                        </small>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
                        <a href="{{ route('songs.show', old('song_id', $songId ?? 0)) }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
                    </div>
                </div>

                <!-- Right: Preview Panel (lyrics only) -->
                <div class="col-lg-6">
                    <div class="card bg-light-subtle h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-eye me-2"></i>Preview</h5>
                        </div>
                        <div class="card-body" id="previewContainer">
                            <div id="previewContent" style="font-family: 'Courier New', monospace; white-space: pre-wrap; line-height: 1.6; font-size: 16px; overflow-x: auto; word-wrap: break-word;">
                                <em class="text-muted">Start typing to see the preview here.</em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
<style>
    .font-monospace {
        font-family: 'Courier New', monospace;
    }
    /* Hide chord lines in preview */
    #previewContent .chord-line {
        display: none !important;
    }
    #previewContent .lyric-line {
        margin: 0;
        padding: 0.1rem 0;
        line-height: 1.6;
    }
    #previewContent .section-heading {
        font-weight: 700;
        font-size: 1.1em;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.15rem;
        margin: 0.5rem 0 0.15rem 0;
        color: var(--text-color);
    }
    #previewContent .lyric-line strong,
    #previewContent .lyric-line em,
    #previewContent .lyric-line u {
        /* keep default styles */;
    }
    @media (max-width: 992px) {
        .col-lg-6 {
            margin-top: 1rem;
        }
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.getElementById('content');
        const previewContent = document.getElementById('previewContent');
        let previewDebounce;

        // ----- Insert text at cursor -----
        function insertText(text) {
            const start = content.selectionStart;
            const end = content.selectionEnd;
            const before = content.value.substring(0, start);
            const after = content.value.substring(end);
            content.value = before + text + after;
            content.focus();
            content.selectionStart = content.selectionEnd = start + text.length;
            updatePreview();
        }

        // ----- Insert chord (simple) -----
        document.querySelectorAll('.insert-chord').forEach(btn => {
            btn.addEventListener('click', function() {
                insertText(this.dataset.chord);
            });
        });

        // ----- Insert section (simple) -----
        document.querySelectorAll('.insert-section').forEach(btn => {
            btn.addEventListener('click', function() {
                insertText(this.dataset.section);
            });
        });

        // ----- Insert formatting (wrap selected text or insert at cursor) -----
        document.querySelectorAll('.insert-format').forEach(btn => {
            btn.addEventListener('click', function() {
                const start = content.selectionStart;
                const end = content.selectionEnd;
                const selectedText = content.value.substring(start, end);
                const openTag = this.dataset.format;
                const closeTag = this.dataset.close;
                if (selectedText) {
                    // Wrap selected text
                    const replacement = openTag + selectedText + closeTag;
                    const before = content.value.substring(0, start);
                    const after = content.value.substring(end);
                    content.value = before + replacement + after;
                    content.focus();
                    content.selectionStart = content.selectionEnd = start + replacement.length;
                } else {
                    // Just insert open + close and place cursor between
                    const before = content.value.substring(0, start);
                    const after = content.value.substring(end);
                    content.value = before + openTag + closeTag + after;
                    content.focus();
                    content.selectionStart = content.selectionEnd = start + openTag.length;
                }
                updatePreview();
            });
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
            .then(res => {
                if (!res.ok) {
                    return res.text().then(text => {
                        throw new Error(text || 'Server error occurred.');
                    });
                }
                return res.json();
            })
            .then(data => {
                previewContent.innerHTML = data.html;
                // Chords are hidden via CSS
            })
            .catch(err => {
                let errorMsg = err.message || 'Could not load preview.';
                if (errorMsg.includes('<')) {
                    const temp = document.createElement('div');
                    temp.innerHTML = errorMsg;
                    errorMsg = temp.textContent || errorMsg;
                    if (errorMsg.length > 200) errorMsg = errorMsg.substring(0, 200) + '…';
                }
                previewContent.innerHTML = `<span class="text-danger">⚠️ ${errorMsg}</span>`;
                console.error('Preview error:', err);
            });
        }

        content.addEventListener('input', function() {
            clearTimeout(previewDebounce);
            previewDebounce = setTimeout(updatePreview, 300);
        });

        // Initial preview if there's old content
        if (content.value.trim()) {
            updatePreview();
        }
    });
</script>
@endpush
