@extends('admin.master')

@section('heading', 'Menu Management')
@section('title', 'Menus')

@section('custom_css')
<style>
    /* Sticky Top Search Header */
    .sticky-menu-header {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 1020;
        background-color: #ffffff;
        border-bottom: 2px solid #e9ecef;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 14px 20px;
    }

    /* Drag and Drop styling */
    .parent-drag-handle {
        cursor: grab;
        padding: 6px 10px;
        color: #6c757d;
        transition: all 0.2s;
    }
    .parent-drag-handle:hover {
        color: #007bff;
        transform: scale(1.2);
    }
    .child-drag-handle {
        cursor: grab;
        padding: 4px 8px;
        color: #adb5bd;
        transition: all 0.2s;
    }
    .child-drag-handle:hover {
        color: #17a2b8;
        transform: scale(1.2);
    }
    .parent-drag-handle:active, .child-drag-handle:active {
        cursor: grabbing;
    }

    /* Highlight matching search items */
    .menu-highlight-match {
        background-color: #fff3cd !important; /* Vivid warm amber/yellow */
        box-shadow: inset 4px 0 0 #ffc107, 0 0 12px rgba(255, 193, 7, 0.45) !important;
        position: relative;
        z-index: 5;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .menu-highlight-match td {
        background-color: #fff3cd !important;
    }
    .match-tag {
        animation: pulseHighlight 1.5s infinite alternate;
        font-size: 0.75rem;
    }
    @keyframes pulseHighlight {
        from { transform: scale(1); }
        to { transform: scale(1.08); }
    }

    /* Sortable placeholders */
    .ui-sortable-placeholder-parent {
        background-color: #e3f2fd !important;
        border: 2px dashed #007bff !important;
        visibility: visible !important;
        height: 55px !important;
        display: table-row-group !important;
    }
    .ui-sortable-placeholder-child {
        background-color: #f1f3f5 !important;
        border: 2px dashed #6c757d !important;
        visibility: visible !important;
        height: 45px !important;
    }
    .ui-sortable-helper {
        display: table !important;
        background-color: #ffffff !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18) !important;
    }

    .order-badge {
        font-size: 0.85rem;
        padding: 4px 8px;
        border-radius: 6px;
    }
</style>
@endsection

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            {{-- Sticky Top Header with Real-Time Search & Actions --}}
            <div class="sticky-menu-header">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-5 col-12 mb-2 mb-md-0">
                        <div class="d-flex align-items-center">
                            <span class="mr-2 text-primary" style="font-size: 1.6rem;"><i class="fas fa-bars"></i></span>
                            <div>
                                <h4 class="font-weight-bolder mb-0 text-dark">Dynamic Sidebar Menus</h4>
                                <small class="text-muted">Drag & drop rows to reorder. Search to highlight.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Search Input Bar --}}
                    <div class="col-lg-5 col-md-4 col-12 mb-2 mb-md-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" id="menuSearchInput" class="form-control border-left-0" placeholder="Search menu, route, url, or role to highlight & swap..." autocomplete="off">
                            <div class="input-group-append" id="clearSearchGroup" style="display: none;">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" title="Clear Search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div id="searchMatchStatus" class="mt-1 small" style="display: none;"></div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-12 text-md-right text-left">
                        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm font-weight-bold">
                            <i class="fas fa-plus mr-1"></i> Add Menu
                        </a>
                    </div>
                </div>
            </div>

            @include('admin.includes.message')

            {{-- Menus Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="menuSortableTable" class="table table-hover table-bordered mb-0">
                        <thead class="bg-cyan text-white">
                            <tr>
                                <th style="width: 55px;" class="text-center" title="Drag to reorder"><i class="fas fa-arrows-alt-v"></i></th>
                                <th style="width: 80px;" class="text-center">Order</th>
                                <th>Menu Name & Icon</th>
                                <th style="width: 140px;">Type</th>
                                <th>Route / URL</th>
                                <th>Active Pattern</th>
                                <th>Visible To Roles</th>
                                <th style="width: 90px;" class="text-center">Status</th>
                                <th style="width: 130px;" class="text-center text-nowrap">Action</th>
                            </tr>
                        </thead>

                        @forelse ($menus as $menu)
                        <tbody class="menu-parent-group" data-id="{{ $menu->id }}">
                            {{-- Top-level Parent / Standalone Menu Row --}}
                            @php
                                $isDropdown = $menu->children->count() > 0 || (empty($menu->route) && empty($menu->url));
                            @endphp
                            <tr class="menu-row parent-menu-row {{ $isDropdown ? 'table-secondary font-weight-bold' : 'table-light' }}" data-id="{{ $menu->id }}" data-level="parent">
                                <td class="text-center align-middle">
                                    <span class="parent-drag-handle" title="Drag to reorder top-level menu position">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-dark order-badge font-weight-bold">{{ $menu->order }}</span>
                                </td>
                                <td class="align-middle menu-name-cell">
                                    @if ($menu->icon)
                                        <i class="{{ $menu->icon }} mr-2 fa-fw"></i>
                                    @endif
                                    <span class="menu-name-text">{{ $menu->name }}</span>
                                    @if ($menu->children->count() > 0)
                                        <span class="badge badge-info ml-2">{{ $menu->children->count() }} Submenus</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if ($isDropdown)
                                        <span class="badge badge-primary"><i class="fas fa-folder mr-1"></i> Dropdown Parent</span>
                                    @else
                                        <span class="badge badge-light border"><i class="fas fa-link mr-1"></i> Standalone</span>
                                    @endif
                                </td>
                                <td class="align-middle menu-route-cell">
                                    @if ($menu->route)
                                        <code>{{ $menu->route }}</code>
                                    @elseif ($menu->url)
                                        <code>{{ $menu->url }}</code>
                                    @else
                                        <span class="text-muted font-italic">(Dropdown Parent)</span>
                                    @endif
                                </td>
                                <td class="align-middle menu-pattern-cell">
                                    <small class="text-muted">{{ $menu->active_pattern ?? '—' }}</small>
                                </td>
                                <td class="align-middle menu-roles-cell">
                                    @foreach ($menu->menuRoles as $mr)
                                        @if ($mr->role)
                                            <span class="badge badge-primary mr-1">{{ $mr->role->name }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center align-middle">
                                    @if ($menu->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle text-nowrap">
                                    <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-info btn-xs mr-1 text-nowrap" title="Edit">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    @php
                                        $childCount = $menu->children->count();
                                    @endphp
                                    @if ($childCount > 0)
                                        <button type="button" class="btn btn-secondary btn-xs text-nowrap" onclick="Swal.fire({icon: 'warning', title: 'Cannot Delete Parent Menu', text: 'This parent menu has {{ $childCount }} child submenu(s). Please delete all child submenus first before deleting this parent menu.'})" title="Cannot delete: has {{ $childCount }} child submenu(s)">
                                            <i class="fa fa-trash-alt"></i> Delete
                                        </button>
                                    @else
                                        <a href="{{ route('admin.menu.delete', $menu->id) }}" class="btn btn-danger btn-xs text-nowrap" onclick="return confirm('Are you sure you want to delete this menu: {{ addslashes($menu->name) }}?')" title="Delete">
                                            <i class="fa fa-trash-alt"></i> Delete
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            {{-- Child Submenus --}}
                            @foreach ($menu->children->sortBy('order') as $child)
                            <tr class="menu-row child-menu-row" data-id="{{ $child->id }}" data-parent-id="{{ $menu->id }}" data-level="child">
                                <td class="text-center align-middle">
                                    <span class="child-drag-handle" title="Drag to reorder submenu position">
                                        <i class="fas fa-grip-lines"></i>
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <small class="text-muted mr-1">↳</small>
                                    <span class="badge badge-secondary order-badge">{{ $child->order }}</span>
                                </td>
                                <td class="align-middle menu-name-cell" style="padding-left: 28px;">
                                    @if ($child->icon)
                                        <i class="{{ $child->icon }} mr-2 fa-fw"></i>
                                    @else
                                        <i class="far fa-circle nav-icon mr-2 fa-fw"></i>
                                    @endif
                                    <span class="menu-name-text">{{ $child->name }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-light border text-muted"><i class="fas fa-file-alt mr-1"></i> Submenu</span>
                                </td>
                                <td class="align-middle menu-route-cell">
                                    @if ($child->route)
                                        <code>{{ $child->route }}</code>
                                    @elseif ($child->url)
                                        <code>{{ $child->url }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="align-middle menu-pattern-cell">
                                    <small class="text-muted">{{ $child->active_pattern ?? '—' }}</small>
                                </td>
                                <td class="align-middle menu-roles-cell">
                                    @foreach ($child->menuRoles as $mr)
                                        @if ($mr->role)
                                            <span class="badge badge-info mr-1">{{ $mr->role->name }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center align-middle">
                                    @if ($child->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle text-nowrap">
                                    <a href="{{ route('admin.menu.edit', $child->id) }}" class="btn btn-info btn-xs mr-1 text-nowrap" title="Edit">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.menu.delete', $child->id) }}" class="btn btn-danger btn-xs text-nowrap" onclick="return confirm('Are you sure you want to delete this submenu: {{ addslashes($child->name) }}?')" title="Delete">
                                        <i class="fa fa-trash-alt"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @empty
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> No menus found. Click <strong>Add Menu</strong> to create your first menu.
                                </td>
                            </tr>
                        </tbody>
                        @endforelse
                    </table>
                </div>
            </div>

            <div class="card-footer bg-light py-2 px-3 small text-muted d-flex justify-content-between align-items-center">
                <span><i class="fas fa-info-circle text-info mr-1"></i> Use the drag handles on the left to move any menu up or down. Changes save automatically.</span>
                <span>Total Top-Level Menus: <strong>{{ $menus->count() }}</strong></span>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        // SweetAlert2 Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            icon: 'success',
            showConfirmButton: false,
            timer: 2500
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Cannot Delete Menu',
                text: "{{ session('error') }}"
            });
        @endif

        // -------------------------------------------------------------
        // 1. Drag & Drop Reordering with jQuery UI
        // -------------------------------------------------------------

        // Reorder top-level parent menus
        $("#menuSortableTable").sortable({
            items: "tbody.menu-parent-group",
            handle: ".parent-drag-handle",
            placeholder: "ui-sortable-placeholder-parent",
            axis: "y",
            cursor: "grabbing",
            opacity: 0.9,
            stop: function(event, ui) {
                saveAllMenuOrders();
            }
        });

        // Reorder child submenus within or between parents
        $("tbody.menu-parent-group").sortable({
            items: "tr.child-menu-row",
            handle: ".child-drag-handle",
            connectWith: "tbody.menu-parent-group",
            placeholder: "ui-sortable-placeholder-child",
            axis: "y",
            cursor: "grabbing",
            opacity: 0.9,
            stop: function(event, ui) {
                saveAllMenuOrders();
            }
        });

        // Save new menu order via AJAX
        function saveAllMenuOrders() {
            var items = [];
            var parentOrder = 1;

            $("tbody.menu-parent-group").each(function() {
                var $group = $(this);
                var parentId = $group.data("id");

                // Update Parent row badge and data
                var $parentRow = $group.find("tr.parent-menu-row");
                $parentRow.find(".order-badge").text(parentOrder);

                items.push({
                    id: parentId,
                    order: parentOrder,
                    parent_id: null
                });
                parentOrder++;

                // Update Child rows badges and data inside this group
                var childOrder = 1;
                $group.find("tr.child-menu-row").each(function() {
                    var $childRow = $(this);
                    var childId = $childRow.data("id");

                    $childRow.find(".order-badge").text(childOrder);
                    $childRow.attr("data-parent-id", parentId);

                    items.push({
                        id: childId,
                        order: childOrder,
                        parent_id: parentId
                    });
                    childOrder++;
                });
            });

            // Send AJAX POST
            $.ajax({
                url: "{{ route('admin.menu.reorder') }}",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    items: items
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Menu positions updated successfully!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message || 'Failed to update order.'
                        });
                    }
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to save menu order. Please try again.'
                    });
                }
            });
        }

        // -------------------------------------------------------------
        // 2. Real-Time Search & Color Highlight Feature
        // -------------------------------------------------------------
        $("#menuSearchInput").on("input keyup", function() {
            var query = $(this).val().toLowerCase().trim();

            // Clear previous highlight styles and indicators
            $(".menu-row").removeClass("menu-highlight-match");
            $(".match-tag").remove();

            if (query === "") {
                $("#clearSearchGroup").hide();
                $("#searchMatchStatus").hide();
                return;
            }

            $("#clearSearchGroup").show();

            var matchCount = 0;
            var $firstMatch = null;

            $(".menu-row").each(function() {
                var $row = $(this);
                var nameText = $row.find(".menu-name-text").text();
                var routeText = $row.find(".menu-route-cell").text();
                var patternText = $row.find(".menu-pattern-cell").text();
                var rolesText = $row.find(".menu-roles-cell").text();

                var fullSearchContent = (nameText + " " + routeText + " " + patternText + " " + rolesText).toLowerCase();

                if (fullSearchContent.indexOf(query) !== -1) {
                    matchCount++;
                    $row.addClass("menu-highlight-match");
                    $row.find(".menu-name-cell").append('<span class="badge badge-warning ml-2 match-tag"><i class="fas fa-highlighter"></i> Matched</span>');

                    if (!$firstMatch) {
                        $firstMatch = $row;
                    }
                }
            });

            if (matchCount > 0) {
                $("#searchMatchStatus").html(
                    '<span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> ' +
                    matchCount + ' item(s) highlighted!</span> ' +
                    '<span class="text-muted ml-1">You can now drag & drop the highlighted row(s) to swap positions.</span>'
                ).show();

                // Smooth scroll to the first match if not in viewport
                if ($firstMatch) {
                    var elementTop = $firstMatch.offset().top;
                    var viewportTop = $(window).scrollTop() + 120;
                    var viewportBottom = viewportTop + $(window).height();

                    if (elementTop < viewportTop || elementTop > viewportBottom) {
                        $('html, body').stop().animate({
                            scrollTop: elementTop - 130
                        }, 300);
                    }
                }
            } else {
                $("#searchMatchStatus").html(
                    '<span class="text-danger"><i class="fas fa-exclamation-circle"></i> No menu matches "' +
                    $('<div>').text(query).html() + '"</span>'
                ).show();
            }
        });

        // Clear Search button
        $("#clearSearchBtn").on("click", function() {
            $("#menuSearchInput").val("").trigger("input");
            $("#menuSearchInput").focus();
        });
    });
</script>
@endsection
