@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="fas fa-users me-2 text-primary"></i>Users</h1>

    {{-- ✅ Only show the "Add User" button if the user has the 'create users' permission --}}
    @can('create users')
        <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Add User
        </a>
    @endcan
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role(s)</th>
                        <th>Joined</th>

                        {{-- ✅ Hide the entire Actions column if user has NO user permissions --}}
                        @if(auth()->user()->can('view users') || auth()->user()->can('edit users') || auth()->user()->can('delete users'))
                            <th class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($userRoles->count())
                                    @foreach($userRoles as $roleName)
                                        <span class="badge bg-primary me-1">{{ $roleName }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted fst-italic">No Role</span>
                                @endif
                            </td>

                            <td>{{ $user->created_at->diffForHumans() }}</td>

                            @if(auth()->user()->can('view users') || auth()->user()->can('edit users') || auth()->user()->can('delete users'))
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">

                                        @can('view users')
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary" title="View User">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('edit users')
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete users')
                                            {{-- ✅ Fixed delete confirmation using standard onclick --}}
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete the user "{{ $user->name }}"? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-users me-2"></i> No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
