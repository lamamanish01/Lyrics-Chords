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
                    <div class="mb-3">
                        <label for="song_id" class="form-label">Song <span class="text-danger">*</span></label>
                        <select name="song_id" id="song_id" class="form-select" required>
                            @foreach($songs as $id => $title)
                                <option value="{{ $id }}" {{ old('song_id', $songId ?? '') == $id ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="section" class="form-label">Section <span class="text-muted">(optional)</span></label>
                        <input type="text" name="section" id="section" class="form-control" value="{{ old('section') }}" placeholder="e.g., Verse, Chorus">
                    </div>

                    <!-- Romanized Version -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Lyrics (Romanized) <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" rows="8" class="form-control font-monospace" required>{{ old('content') }}</textarea>
                        <small class="text-muted">Use <code>[Chord]</code> syntax. Section headers like <code>[Verse]</code> become bold headings.</small>
                    </div>

                    <!-- Nepali Version -->
                    <div class="mb-3">
                        <label for="content_nepali" class="form-label">Lyrics (Nepali Devnagari) <span class="text-muted">(optional)</span></label>
                        <textarea name="content_nepali" id="content_nepali" rows="8" class="form-control font-monospace">{{ old('content_nepali') }}</textarea>
                        <small class="text-muted">Same chord syntax – chords will be displayed above the lyrics.</small>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
                        <a href="{{ route('songs.show', old('song_id', $songId ?? 0)) }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
                    </div>
                </div>

                <!-- Right: Preview Panel -->
                <div class="col-lg-6">
                    <div class="card bg-light-subtle h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-eye me-2"></i>Preview (Romanized)</h5>
                        </div>
                        <div class="card-body" id="previewContainer">
                            <div id="previewContent">
                                <em class="text-muted">Start typing to see the preview here.</em>
                            </div>
                            <!-- Debug output (hidden by default) -->
                            <div id="debugOutput" class="mt-2 small text-danger" style="display:none; border-top:1px solid #ddd; padding-top:0.5rem;"></div>
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
        font-weight: 700;
        font-size: 1.1em;
        border-bottom: 1px solid var(--border-color, #dee2e6);
        padding-bottom: 0.15rem;
        margin: 0.75rem 0 0.25rem 0;
        color: var(--text-color, #212529);
    }
    #previewContent {
        font-size: clamp(14px, 2vw, 18px);
        overflow-x: auto;
        overflow-y: auto;
        max-height: 500px;
        width: 100%;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.25rem;
        word-break: break-word;
        white-space: normal;
    }

    @media (max-width: 576px) {
        #previewContent {
            font-size: 14px !important;
            padding: 0.75rem !important;
            max-height: 350px !important;
        }
        .card-body {
            padding: 0.75rem;
        }
    }
    @media (max-width: 992px) {
        .col-lg-6 {
            margin-top: 1rem;
        }
        .bg-light-subtle {
            min-height: auto !important;
        }
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.getElementById('content');
        const previewContent = document.getElementById('previewContent');
        const debugOutput = document.getElementById('debugOutput');

        function debug(msg) {
            debugOutput.style.display = 'block';
            debugOutput.innerHTML = msg;
            console.debug('[Preview Debug]', msg);
        }

        function clearDebug() {
            debugOutput.style.display = 'none';
            debugOutput.innerHTML = '';
        }

        function updatePreview() {
            const text = content.value.trim();
            if (!text) {
                previewContent.innerHTML = '<em class="text-muted">Enter some lyrics to see the preview.</em>';
                clearDebug();
                return;
            }

            // 🔥 CRITICAL FIX: Use a protocol-relative (or absolute) URL
            // This ensures the request uses the same protocol as the page (HTTPS)
            const url = '/lyrics/preview';

            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            debug('→ Fetching: ' + window.location.origin + url);
            debug('→ Using CSRF: ' + csrfToken.substring(0, 10) + '…');

            fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content: text })
            })
            .then(response => {
                debug('→ Status: ' + response.status + ' ' + response.statusText);
                if (!response.ok) {
                    return response.text().then(text => {
                        let errorMsg = text || 'Server error (status ' + response.status + ')';
                        if (errorMsg.includes('<')) {
                            const temp = document.createElement('div');
                            temp.innerHTML = errorMsg;
                            errorMsg = temp.textContent || errorMsg;
                            if (errorMsg.length > 200) errorMsg = errorMsg.substring(0, 200) + '…';
                        }
                        throw new Error(errorMsg);
                    });
                }
                return response.json();
            })
            .then(data => {
                debug('→ Response OK, HTML length: ' + (data.html ? data.html.length : '0'));
                if (data.html) {
                    previewContent.innerHTML = data.html;
                    clearDebug();
                } else {
                    previewContent.innerHTML = '<span class="text-warning">⚠️ Empty response from server.</span>';
                    debug('⚠️ Empty HTML in response');
                }
            })
            .catch(error => {
                console.error('Preview error:', error);
                let msg = error.message || 'Could not load preview.';
                previewContent.innerHTML = `<span class="text-danger">⚠️ ${msg}</span>`;
                debug('❌ Error: ' + msg);
            });
        }

        content.addEventListener('input', function() {
            clearTimeout(window.previewDebounce);
            window.previewDebounce = setTimeout(updatePreview, 300);
        });

        // Initial preview if content has value
        if (content.value.trim()) {
            updatePreview();
        }
    });
</script>
@endpush
