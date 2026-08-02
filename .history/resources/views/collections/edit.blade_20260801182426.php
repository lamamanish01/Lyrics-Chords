@extends('layouts.app')

@section('title', 'Edit Collection')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Collection: {{ $collection->name }}</h3></div>
    <div class="card-body">
        <form action="{{ route('collections.update', $collection) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $collection->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $collection->slug) }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="2" class="form-control">{{ old('description', $collection->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="color" name="color" id="color" class="form-control form-control-color" value="{{ old('color', $collection->color) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Select Songs</label>
                <select name="songs[]" class="form-select" multiple size="6">
                    @foreach($songs as $song)
                        <option value="{{ $song->id }}" {{ $collection->songs->contains($song->id) ? 'selected' : '' }}>
                            {{ $song->title }} – {{ $song->artist }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('collections.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
