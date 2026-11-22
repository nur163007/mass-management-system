@extends('admin.master')

@section('heading', 'Edit Menu')
@section('title', 'Edit Menu')

@section('custom_css')
<style>
    .menu-type-selector .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 22px;
        transition: all 0.25s ease;
    }
    .menu-type-selector .btn.active {
        box-shadow: 0 4px 10px rgba(23, 162, 184, 0.25);
    }
</style>
@endsection

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-info card-outline shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold text-dark mb-0">
                                <i class="fas fa-edit text-info mr-1"></i> Edit Menu: <span class="text-primary">{{ $menu->name }}</span>
                            </h3>
                            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Menus
                            </a>
                        </div>
                    </div>

                    @include('admin.includes.message')

                    @if ($menu->children->count() > 0)
                        <div class="alert alert-warning mx-3 mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle mr-1"></i> 
                            This is a parent dropdown menu containing <strong>{{ $menu->children->count() }} submenus</strong>.
                        </div>
                    @endif

                    <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" id="editMenuForm">
                        @csrf
                        <div class="card-body">
                            {{-- Menu Type Selector --}}
                            @php
                                $currentType = old('menu_type', $isParent ? 'parent' : 'child');
                            @endphp
                            <div class="form-group mb-4 p-3 bg-light rounded border">
                                <label class="d-block font-weight-bold mb-2 text-dark">
                                    <i class="fas fa-layer-group text-info mr-1"></i> Menu Type <span class="text-danger">*</span>
                                </label>
                                <div class="btn-group btn-group-toggle menu-type-selector d-flex flex-wrap" data-toggle="buttons">
                                    <label class="btn btn-outline-primary {{ $currentType == 'child' ? 'active' : '' }} mr-2 mb-2">
                                        <input type="radio" name="menu_type" id="type_child" value="child" autocomplete="off" {{ $currentType == 'child' ? 'checked' : '' }}>
                                        <i class="fas fa-link mr-1"></i> Child / Standalone Menu
                                    </label>
                                    <label class="btn btn-outline-info {{ $currentType == 'parent' ? 'active' : '' }} mb-2">
                                        <input type="radio" name="menu_type" id="type_parent" value="parent" autocomplete="off" {{ $currentType == 'parent' ? 'checked' : '' }}>
                                        <i class="fas fa-folder mr-1"></i> Parent Menu (Dropdown Group)
                                    </label>
                                </div>
                                <div class="mt-2 text-muted small" id="type_helper_text">
                                    <span id="child_help_msg"><i class="fas fa-info-circle text-primary"></i> <strong>Child / Standalone Menu:</strong> Has a route/URL. Can be assigned under a parent dropdown or left as a top-level clickable menu (like Dashboard).</span>
                                    <span id="parent_help_msg" style="display: none;"><i class="fas fa-info-circle text-info"></i> <strong>Parent Menu:</strong> Works as a folder/dropdown header that holds submenus. Has no direct route or URL.</span>
                                </div>
                            </div>

                            {{-- Menu Name & Icon --}}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name">Menu Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="icon">Icon Class</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i id="icon_preview" class="{{ old('icon', $menu->icon) }}"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $menu->icon) }}">
                                    </div>
                                    <small class="form-text text-muted">FontAwesome classes (e.g. <code>fas fa-tachometer-alt text-secondary</code>, <code>far fa-circle nav-icon</code>).</small>
                                    @error('icon')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Child-Only Section: Parent Selection, Route, URL, Pattern --}}
                            <div id="child_fields_container" style="{{ $currentType == 'parent' ? 'display: none;' : '' }}">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="parent_id">
                                            Parent Menu <small class="text-muted font-italic">(Optional - only dropdown folders shown)</small>
                                        </label>
                                        <select class="form-control" id="parent_id" name="parent_id">
                                            <option value="">None (Standalone Top-Level Menu)</option>
                                            @foreach ($parentMenus as $parent)
                                                <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
                                                    📁 {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Select a parent if this is a submenu item. Leave blank if this is a standalone top-level menu.</small>
                                        @error('parent_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="route">Route Name</label>
                                        <input type="text" class="form-control" id="route" name="route" value="{{ old('route', $menu->route) }}" placeholder="e.g. admin.dashboard">
                                        <small class="form-text text-muted">Laravel route name (preferred).</small>
                                        @error('route')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="url">Direct URL (Optional)</label>
                                        <input type="text" class="form-control" id="url" name="url" value="{{ old('url', $menu->url) }}" placeholder="e.g. /custom-url">
                                        <small class="form-text text-muted">Used if Route Name is not provided.</small>
                                        @error('url')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label for="active_pattern">Active URL Pattern</label>
                                        <input type="text" class="form-control" id="active_pattern" name="active_pattern" value="{{ old('active_pattern', $menu->active_pattern) }}" placeholder="e.g. admin/member/*,admin/member/addmembers">
                                        <small class="form-text text-muted">Comma-separated patterns to keep this menu highlighted when active.</small>
                                        @error('active_pattern')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="1" {{ old('status', $menu->status) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $menu->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Roles --}}
                            <div class="form-group mt-3">
                                <label class="d-block font-weight-bold">Assign to Roles <span class="text-danger">*</span></label>
                                <small class="form-text text-muted mb-2">Select which user roles will see this menu item:</small>
                                <div class="row">
                                    @foreach ($roles as $role)
                                        @php
                                            $checked = is_array(old('roles')) ? in_array($role->id, old('roles')) : in_array($role->id, $assignedRoleIds);
                                        @endphp
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}" {{ $checked ? 'checked' : '' }}>
                                                <label for="role_{{ $role->id }}" class="custom-control-label font-weight-normal">
                                                    {{ $role->name }}
                                                    @if (in_array($role->id, [1, 2, 3]))
                                                        <small class="text-muted">({{ $role->slug }})</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('roles')
                                    <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer bg-light text-right">
                            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-info px-4"><i class="fas fa-save mr-1"></i> Update Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        function toggleMenuType() {
            var selectedType = $('input[name="menu_type"]:checked').val();
            if (selectedType === 'parent') {
                $('#child_fields_container').slideUp(200);
                $('#parent_help_msg').show();
                $('#child_help_msg').hide();
                // Clear route, url, pattern, parent_id values when parent is chosen
                $('#parent_id').val('');
                $('#route').val('');
                $('#url').val('');
                $('#active_pattern').val('');
            } else {
                $('#child_fields_container').slideDown(200);
                $('#parent_help_msg').hide();
                $('#child_help_msg').show();
            }
        }

        $('input[name="menu_type"]').on('change', function() {
            toggleMenuType();
        });

        // Icon preview update
        $('#icon').on('input', function() {
            var iconClass = $(this).val().trim();
            $('#icon_preview').attr('class', iconClass || 'far fa-circle nav-icon');
        });

        // Initialize state
        toggleMenuType();
    });
</script>
@endsection
