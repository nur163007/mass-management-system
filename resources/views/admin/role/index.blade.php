@extends('admin.master')

@section('heading', 'Role Management')
@section('title', 'Roles')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">User Roles</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{ route('admin.role.create') }}" class="viewall"><i class="fas fa-plus"></i> Add Role</a>
                </div>
            </div>

            @include('admin.includes.message')

            <div class="card-body">
                <table id="all-category" class="table table-bordered table-hover">
                    <thead class="bg-cyan">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Role Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th style="width: 120px;">Members</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                <strong>{{ $role->name }}</strong>
                                @if (in_array($role->id, [1, 2, 3]))
                                    <span class="badge badge-primary ml-1">System Role</span>
                                @endif
                            </td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>{{ $role->description ?? '—' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $role->members_count }} Members</span>
                            </td>
                            <td>
                                @if ($role->status == 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-info btn-xs" title="Edit">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                @if (!in_array($role->id, [1, 2, 3]))
                                    <a href="{{ route('admin.role.delete', $role->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this role?')" title="Delete">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
    $(function() {
        $("#all-category").DataTable({
            "responsive": true,
            "autoWidth": false
        });
    });
</script>
@endsection
