<div class="row g-3">
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $song->title ?? '') }}" placeholder="Title" required>
            <label for="title">Title
                <span class="text-danger">*</span>
            </label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" name="artist" id="artist" class="form-control" value="{{ old('artist', $song->artist ?? '') }}" placeholder="Artist" required>
            <label for="artist">Artist
                <span class="text-danger">*</span>
            </label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating">
            <input type="text" name="album" id="album" class="form-control" value="{{ old('album', $song->album ?? '') }}" placeholder="Album">
            <label for="album">Album</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating">
            <input type="number" name="year" id="year" class="form-control" value="{{ old('year', $song->year ?? '') }}" placeholder="Year">
            <label for="year">Year</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating">
            <input type="text" name="genre" id="genre" class="form-control" value="{{ old('genre', $song->genre ?? '') }}" placeholder="Genre">
            <label for="genre">Genre</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <input type="text" name="key" id="key" class="form-control" value="{{ old('key', $song->key ?? '') }}" placeholder="Key">
            <label for="key">Original Key</label>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Collections</label>
        <select name="collections[]" class="form-select" multiple size="4">
            @foreach($collections as $collection)
                <option value="{{ $collection->id }}"
                    {{ (isset($song) && $song->collections->contains($collection->id)) ? 'selected' : '' }}>
                    {{ $collection->name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
    </div>
</div>
