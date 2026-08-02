@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user me-2"></i>{{ $user->name }}</h3>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Name</strong>
                <p class="mb-3">{{ $user->name }}</p>
            </div>
            <div class="col-md-6">
                <strong>Email</strong>
                <p class="mb-3">{{ $user->email }}</p>
            </div>
            <div class="col-md-6">
                <strong>Role</strong>
                <p class="mb-3">
                    <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <strong>Joined</strong>
                <p class="mb-3">{{ $user->created_at->format('F j, Y, g:i A') }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back to Users</a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i>Edit</a>
        </div>
    </div>
</div>
@endsection
