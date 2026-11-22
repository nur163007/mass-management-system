@extends('admin.master')

@section('heading', 'Edit Role')
@section('title', 'Edit Role')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit"></i> Edit Role: {{ $role->name }}</h3>
                    </div>

                    @include('admin.includes.message')

                    <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $role->slug) }}" {{ in_array($role->id, [1, 2, 3]) ? 'readonly' : '' }}>
                                @if (in_array($role->id, [1, 2, 3]))
                                    <small class="text-muted">Slug cannot be changed for core system roles.</small>
                                @endif
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1" {{ old('status', $role->status) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $role->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('admin.role.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Update Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
