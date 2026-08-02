@extends('layouts.app')

@section('title', 'Edit Lyric')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Lyric</h3></div>
    <div class="card-body">
        <form action="{{ route('lyrics.update', $lyric) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="song_id" class="form-label">Song</label>
                <select name="song_id" id="song_id" class="form-select" required>
                    @foreach($songs as $id => $title)
                        <option value="{{ $id }}" {{ old('song_id', $lyric->song_id) == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input type="text" name="section" id="section" class="form-control" value="{{ old('section', $lyric->section) }}">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Lyrics & Chords</label>
                <textarea name="content" id="content" rows="10" class="form-control" required>{{ old('content', $lyric->content) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('songs.show', $lyric->song_id) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
