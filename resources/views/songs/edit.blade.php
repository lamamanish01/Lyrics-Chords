@extends('layouts.app')

@section('title', 'Edit Song')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Edit Song: {{ $song->title }}</h3></div>
    <div class="card-body">
        <form action="{{ route('songs.update', $song) }}" method="POST">
            @csrf @method('PUT')
            @include('songs._form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('songs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
