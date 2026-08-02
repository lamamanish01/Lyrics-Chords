@extends('layouts.app')

@section('title', 'Add Lyric')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Add Lyric</h3></div>
    <div class="card-body">
        <form action="{{ route('lyrics.store') }}" method="POST">
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
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('songs.show', old('song_id', $songId ?? 0)) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
