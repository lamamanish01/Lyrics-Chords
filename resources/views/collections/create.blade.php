@extends('layouts.app')

@section('title', 'Create Collection')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Create Collection</h3></div>
    <div class="card-body">
        <form action="{{ route('collections.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="2" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="color" name="color" id="color" class="form-control form-control-color" value="{{ old('color', '#6c757d') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Select Songs</label>
                <select name="songs[]" class="form-select" multiple size="6">
                    @foreach($songs as $song)
                        <option value="{{ $song->id }}" {{ in_array($song->id, old('songs', [])) ? 'selected' : '' }}>
                            {{ $song->title }} – {{ $song->artist }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('collections.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
