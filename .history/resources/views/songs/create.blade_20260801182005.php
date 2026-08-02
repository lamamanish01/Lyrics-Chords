@extends('layouts.app')

@section('title', 'Create Song')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Create New Song</h3></div>
    <div class="card-body">
        <form action="{{ route('songs.store') }}" method="POST">
            @csrf
            @include('songs._form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('songs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
