<?php $__env->startSection('heading', 'All Members'); ?>
<?php $__env->startSection('title', 'All Members | MMS'); ?>

<?php $__env->startSection('custom_css'); ?>
    <style>
        :root {
            --vm-radius: 16px;
            --vm-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hero Banner */
        .vm-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            border-radius: var(--vm-radius);
            color: #ffffff;
            padding: 24px 28px;
            margin-bottom: 22px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        }

        .vm-hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.24) 0%, rgba(56, 189, 248, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .vm-hero-title {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .vm-hero-sub {
            font-size: 0.88rem;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .vm-hero-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: var(--vm-transition);
        }

        .vm-hero-btn:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.45);
            text-decoration: none;
        }

        /* Summary Stat Cards */
        .vm-stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--vm-transition);
            height: 100%;
        }

        .vm-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.08);
        }

        .vm-stat-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .vm-stat-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .vm-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* Table Panel */
        .vm-panel {
            background: #ffffff;
            border-radius: var(--vm-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .vm-panel-head {
            padding: 16px 22px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .vm-panel-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .vm-table-wrap {
            padding: 20px 22px;
        }

        .vm-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px;
            overflow: hidden;
        }

        .vm-table thead th {
            background: #f1f5f9 !important;
            color: #334155 !important;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 13px 14px !important;
            border-bottom: 1px solid #e2e8f0 !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
        }

        .vm-table tbody td {
            padding: 12px 14px !important;
            vertical-align: middle !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 0.89rem;
            color: #1e293b;
        }

        .vm-table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        .vm-thumb {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
        }

        .vm-role-select {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.82rem;
            font-weight: 600;
            min-width: 110px;
        }

        .vm-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: var(--vm-transition);
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 5px 10px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
        }

        /* Custom Toggle Switch (no external plugin needed) */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 64px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            border-radius: 28px;
            transition: 0.3s;
            display: flex;
            align-items: center;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            height: 20px;
            width: 20px;
            left: 4px;
            background-color: white;
            border-radius: 50%;
            transition: 0.3s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked+.toggle-slider {
            background-color: #10b981;
        }

        .toggle-switch input:checked+.toggle-slider::before {
            transform: translateX(36px);
        }

        .toggle-label {
            position: absolute;
            font-size: 0.6rem;
            font-weight: 700;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            letter-spacing: 0.3px;
        }

        .toggle-label-on {
            left: 7px;
            color: #fff;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .toggle-label-off {
            right: 7px;
            color: #64748b;
            transition: opacity 0.2s;
        }

        .toggle-switch input:checked~.toggle-label-on {
            opacity: 1;
        }

        .toggle-switch input:checked~.toggle-label-off {
            opacity: 0;
        }

        @media (max-width: 767px) {
            .vm-table-wrap {
                padding: 12px !important;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
    <?php
        $totalMembersCount = count($members);
        $activeMembersCount = collect($members)->where('status', 1)->count();
        $managersCount = collect($members)->where('role_id', 2)->count();
    ?>

    <section class="content">
        <div class="container-fluid">

            <!-- Hero Header -->
            <div class="vm-hero">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h3 class="vm-hero-title">
                            <i class="fas fa-users mr-2 text-info"></i> Mess Members Directory
                        </h3>
                        <p class="vm-hero-sub">
                            View, manage roles, toggle active status, and update member profiles from one place.
                        </p>
                    </div>
                    <div>
                        <?php if(session('role') == 1): ?>
                            <a href="<?php echo e(route('admin.add-member')); ?>" class="vm-hero-btn">
                                <i class="fas fa-user-plus"></i> Add Member
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Summary Stat Cards -->
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                    <div class="vm-stat-card">
                        <div>
                            <div class="vm-stat-label">Total Members</div>
                            <div class="vm-stat-value"><?php echo e($totalMembersCount); ?></div>
                        </div>
                        <div class="vm-stat-icon" style="background: #eef2ff; color: #4f46e5;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                    <div class="vm-stat-card">
                        <div>
                            <div class="vm-stat-label">Active Members</div>
                            <div class="vm-stat-value text-success"><?php echo e($activeMembersCount); ?></div>
                        </div>
                        <div class="vm-stat-icon" style="background: #ecfdf5; color: #059669;">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="vm-stat-card">
                        <div>
                            <div class="vm-stat-label">Assigned Managers</div>
                            <div class="vm-stat-value text-warning"><?php echo e($managersCount); ?></div>
                        </div>
                        <div class="vm-stat-icon" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members Table Panel -->
            <div class="vm-panel">
                <div class="vm-panel-head">
                    <h5 class="vm-panel-title">
                        <i class="fas fa-address-book text-primary mr-2"></i> All Registered Members
                    </h5>
                    <span class="badge badge-light border text-muted px-3 py-1" style="border-radius: 50px;">
                        <?php echo e($totalMembersCount); ?> Records
                    </span>
                </div>

                <div class="vm-table-wrap">
                    <div class="table-responsive">
                        <table id="all-category" class="table vm-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Phone No</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Photo</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-muted font-weight-bold"><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <strong class="text-dark"><?php echo e($member->full_name); ?></strong>
                                            <?php if($member->role_id == 2): ?>
                                                <span class="badge badge-warning ml-1 px-2 py-1"
                                                    style="border-radius: 6px;">Manager</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="text-nowrap">0<?php echo e($member->phone_no); ?></span>
                                        </td>
                                        <td><?php echo e($member->email); ?></td>
                                        <td>
                                            <?php if(session('role') == 1): ?>
                                                <select class="form-control form-control-sm vm-role-select changeRole"
                                                    data-id="<?php echo e($member->id); ?>" data-old-role="<?php echo e($member->role_id); ?>">
                                                    <?php if(isset($roles) && count($roles) > 0): ?>
                                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($r->id); ?>"
                                                                <?php echo e($member->role_id == $r->id ? 'selected' : ''); ?>>
                                                                <?php echo e($r->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <option value="3"
                                                            <?php echo e($member->role_id == 3 ? 'selected' : ''); ?>>User</option>
                                                        <option value="2"
                                                            <?php echo e($member->role_id == 2 ? 'selected' : ''); ?>>Manager</option>
                                                    <?php endif; ?>
                                                </select>
                                            <?php else: ?>
                                                <span
                                                    class="badge <?php echo e($member->role_id == 2 ? 'badge-warning' : 'badge-info'); ?> px-2 py-1">
                                                    <?php echo e($member->role_name ?? ($member->role_id == 2 ? 'Manager' : 'User')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $defaultAvatar = asset('assets/admin/dist/img/avatar5.png');
                                                $photoSrc = ($member->photo && $member->photo !== 'null')
                                                    ? asset('uploads/members/profile/' . $member->photo)
                                                    : $defaultAvatar;
                                            ?>
                                            <img class="vm-thumb"
                                                src="<?php echo e($photoSrc); ?>"
                                                onerror="this.onerror=null;this.src='<?php echo e($defaultAvatar); ?>'"
                                                alt="Profile">
                                        </td>
                                        <td>
                                            <?php if(session('role') == 1): ?>
                                                <label class="toggle-switch mb-0">
                                                    <input type="checkbox" id="memberStatus" data-id="<?php echo e($member->id); ?>"
                                                        <?php echo e($member->status == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-slider"></span>
                                                    <span class="toggle-label toggle-label-on">ON</span>
                                                    <span class="toggle-label toggle-label-off">OFF</span>
                                                </label>
                                            <?php else: ?>
                                                <?php if($member->status == 1): ?>
                                                    <span class="badge badge-success px-2 py-1">Active</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary px-2 py-1">Inactive</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center text-nowrap" style="width: 140px">
                                            <a href="<?php echo e(route('admin.show-member', $member->id)); ?>"
                                                class="btn btn-outline-primary vm-action-btn mr-1" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if(session('role') == 1): ?>
                                                <a href="<?php echo e(route('admin.edit-member', $member->id)); ?>"
                                                    class="btn btn-outline-info vm-action-btn mr-1" title="Edit Member">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.delete', $member->id)); ?>"
                                                    class="btn btn-outline-danger vm-action-btn" title="Delete Member"
                                                    onclick="return confirm('Are you sure you want to delete this member?')">
                                                    <i class="fa fa-trash-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script>
        var base_url = window.location.origin;

        // SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            icon: 'success',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function() {
            // Store initial role values when page loads
            $('.changeRole').each(function() {
                $(this).data('old-value', $(this).val());
            });

            $('body').on('change', '#memberStatus', function() {
                var id = $(this).attr('data-id');
                var $checkbox = $(this);
                var isChecked = this.checked;
                var status = isChecked ? 1 : 0;

                $.ajax({
                    url: 'memberStatus/' + id + '/' + status,
                    method: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response.message) {
                            Toast.fire({
                                type: 'success',
                                title: 'Status updated successfully'
                            });
                        }
                    },
                    error: function(xhr) {
                        $checkbox.prop('checked', !isChecked);
                        var errorMsg = 'Failed to update status';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        Toast.fire({
                            type: 'error',
                            title: errorMsg
                        });
                    }
                });
            });

            // Change role functionality
            $('body').on('change', '.changeRole', function() {
                var memberId = $(this).attr('data-id');
                var $select = $(this);
                var oldRole = $select.attr('data-old-role') || $select.val();
                var newRole = $(this).val();

                if (oldRole == newRole) {
                    $select.val(oldRole);
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Changing member role between Manager and User.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed || result.value === true) {
                        $.ajax({
                            url: base_url + '/admin/member/changeRole/' + memberId,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                role_id: parseInt(newRole)
                            },
                            dataType: 'json',
                            success: function(response) {
                                Toast.fire({
                                    type: 'success',
                                    title: response.message ||
                                        'Role changed successfully'
                                });
                                $.ajax({
                                    url: '<?php echo e(route('admin.view-member')); ?>',
                                    method: 'GET',
                                    success: function(html) {
                                        var $response = $(html);
                                        var $newTbody = $response.find(
                                            '#all-category tbody');

                                        if ($newTbody.length > 0) {
                                            $('#all-category').DataTable()
                                                .destroy();
                                            $('#all-category tbody').html(
                                                $newTbody.html());
                                            $('#all-category').DataTable({
                                                "responsive": true,
                                                "autoWidth": false,
                                                "drawCallback": function(
                                                    settings) {
                                                    $('.changeRole')
                                                        .each(
                                                            function() {
                                                                if (!
                                                                    $(
                                                                        this
                                                                    )
                                                                    .data(
                                                                        'old-value'
                                                                    )
                                                                ) {
                                                                    $(this)
                                                                        .data(
                                                                            'old-value',
                                                                            $(
                                                                                this
                                                                            )
                                                                            .val()
                                                                        );
                                                                }
                                                            });
                                                }
                                            });
                                        }
                                    },
                                    error: function() {
                                        location.reload();
                                    }
                                });
                            },
                            error: function(xhr) {
                                var errorMsg = 'Failed to change role';
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMsg = xhr.responseJSON.error;
                                }
                                Toast.fire({
                                    type: 'error',
                                    title: errorMsg
                                });
                                $select.val(oldRole);
                            }
                        });
                    } else {
                        $select.val(oldRole);
                    }
                });
            });
        });

        $(function() {
            $("#all-category").DataTable({
                "responsive": true,
                "autoWidth": false,
                "drawCallback": function(settings) {
                    $('.changeRole').each(function() {
                        if (!$(this).data('old-value')) {
                            $(this).data('old-value', $(this).val());
                        }
                    });
                }
            });

            $('.changeRole').each(function() {
                $(this).data('old-value', $(this).val());
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/member/view_members.blade.php ENDPATH**/ ?>