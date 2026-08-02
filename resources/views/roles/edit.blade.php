@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-edit me-2"></i>Edit Role</h1>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PATCH')

            {{-- Role Name --}}
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $role->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>

            {{-- Permissions --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Permissions</label>
                <div class="mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="selectAll">
                        <label class="form-check-label" for="selectAll">Select All</label>
                    </div>
                </div>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-3 col-sm-4 col-6 mb-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox"
                                       name="permissions[]" value="{{ $permission->name }}"
                                       id="perm-{{ $permission->id }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update</button>
        </form>
    </div>
</div>

{{-- ✅ Push the script to the 'js' stack (matches layout's @stack('js')) --}}
@push('js')
<script>
    (function() {
        // Ensure the DOM is fully loaded before running
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        function init() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');

            // Debugging logs – open browser console (F12) to see these
            console.log('🔍 Select All element:', selectAll);
            console.log('📦 Permission checkboxes found:', checkboxes.length);

            if (!selectAll) {
                console.error('❌ #selectAll not found! Check the ID.');
                return;
            }

            if (checkboxes.length === 0) {
                console.warn('⚠️ No permission checkboxes found. Is $permissions empty?');
            }

            // 1. Toggle all checkboxes when "Select All" is clicked
            selectAll.addEventListener('change', function() {
                const isChecked = this.checked;
                checkboxes.forEach(cb => cb.checked = isChecked);
            });

            // 2. Auto-check/uncheck "Select All" based on individual checkboxes
            checkboxes.forEach(function(cb) {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                });
            });

            console.log('✅ Select All functionality attached successfully!');
        }
    })();
</script>
@endpush
@endsection
