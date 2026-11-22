@extends('admin.master')

@section('heading', 'Create Role')
@section('title', 'Add Role')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-plus-circle"></i> Create New Role</h3>
                    </div>

                    @include('admin.includes.message')

                    <form action="{{ route('admin.role.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Accountant, Assistant Manager" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug (Optional)</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. accountant (leave blank to auto-generate)">
                                @error('slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter brief description of this role">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('admin.role.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
