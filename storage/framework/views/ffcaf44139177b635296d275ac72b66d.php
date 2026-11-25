<?php $__env->startSection('heading', 'Add Member'); ?>
<?php $__env->startSection('title', 'Add Member | MMS'); ?>

<?php $__env->startSection('custom_css'); ?>
    <style>
        :root {
            --am-radius: 16px;
            --am-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hero Banner */
        .am-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            border-radius: var(--am-radius);
            color: #ffffff;
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        }

        .am-hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.28) 0%, rgba(99, 102, 241, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .am-hero-title {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .am-hero-sub {
            font-size: 0.88rem;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .am-hero-btn {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: var(--am-transition);
        }

        .am-hero-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Form Panel */
        .am-panel {
            background: #ffffff;
            border-radius: var(--am-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .am-panel-head {
            padding: 18px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .am-panel-title {
            font-size: 1.02rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .am-panel-body {
            padding: 26px 24px;
        }

        .am-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .am-input,
        .am-select {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.92rem;
            height: 44px;
            padding: 8px 14px;
            color: #0f172a;
            background-color: #ffffff;
            transition: var(--am-transition);
        }

        .am-input:focus,
        .am-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.14) !important;
            outline: none;
        }

        /* File Upload Box */
        .am-upload-box {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            transition: var(--am-transition);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .am-upload-box:hover {
            border-color: #6366f1;
            background: #f5f3ff;
        }

        .am-preview-img {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #cbd5e1;
            display: none;
            flex-shrink: 0;
        }

        .am-submit-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-size: 0.95rem;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: var(--am-transition);
            cursor: pointer;
        }

        .am-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.45);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<section class="content">
    <div class="container-fluid">

        <!-- Hero Banner -->
        <div class="am-hero">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h3 class="am-hero-title">
                        <i class="fas fa-user-plus mr-2 text-info"></i> Add New Mess Member
                    </h3>
                    <p class="am-hero-sub">
                        Register a new member with contact information, role assignment, and optional photo/NID documents.
                    </p>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.view-member')); ?>" class="am-hero-btn">
                        <i class="fas fa-users text-info"></i> All Members
                    </a>
                </div>
            </div>
        </div>

        <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Form Panel -->
        <div class="am-panel">
            <div class="am-panel-head">
                <h5 class="am-panel-title">
                    <i class="fas fa-id-card text-primary"></i>
                    Member Registration Form
                </h5>
                <span class="badge badge-light border text-muted px-3 py-1" style="border-radius: 50px;">
                    <i class="fas fa-shield-alt mr-1 text-primary"></i> Super Admin Access
                </span>
            </div>

            <div class="am-panel-body">
                <form method="POST" enctype="multipart/form-data" id="form" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="form-group col-md-6 mb-4">
                            <label for="full_name" class="am-label">
                                <i class="fas fa-user text-primary"></i> Full Name <span class="text-danger">*</span>
                            </label>
                            <input class="form-control am-input" type="text" id="full_name" name="full_name"
                                placeholder="Enter member's full name" autocomplete="off">
                            <?php if($errors->has('full_name')): ?>
                                <small class="text-danger font-weight-bold"><?php echo e($errors->first('full_name')); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-4">
                            <label for="phone_no" class="am-label">
                                <i class="fas fa-phone-alt text-success"></i> Phone No <span class="text-danger">*</span>
                            </label>
                            <input class="form-control am-input" type="text" id="phone_no" name="phone_no"
                                placeholder="Enter phone number" autocomplete="off">
                            <?php if($errors->has('phone_no')): ?>
                                <small class="text-danger font-weight-bold"><?php echo e($errors->first('phone_no')); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-4">
                            <label for="address" class="am-label">
                                <i class="fas fa-map-marker-alt text-danger"></i> Permanent Address
                            </label>
                            <input class="form-control am-input" type="text" id="address" name="address"
                                placeholder="Enter permanent address" autocomplete="off">
                            <?php if($errors->has('address')): ?>
                                <small class="text-danger font-weight-bold"><?php echo e($errors->first('address')); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-4">
                            <label for="email" class="am-label">
                                <i class="fas fa-envelope text-info"></i> Email
                            </label>
                            <input class="form-control am-input" type="email" id="email" name="email"
                                placeholder="Enter email address" autocomplete="off">
                            <?php if($errors->has('email')): ?>
                                <small class="text-danger font-weight-bold"><?php echo e($errors->first('email')); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-4">
                            <label for="role_id" class="am-label">
                                <i class="fas fa-user-tag text-warning"></i> Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-control am-select" id="role_id" name="role_id" required autocomplete="off">
                                <?php if(isset($roles) && count($roles) > 0): ?>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($r->id); ?>" <?php echo e(old('role_id', '3') == $r->id ? 'selected' : ''); ?>>
                                            <?php echo e($r->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <option value="3" selected>User</option>
                                    <option value="2">Manager</option>
                                <?php endif; ?>
                            </select>
                            <?php if($errors->has('role_id')): ?>
                                <small class="text-danger font-weight-bold"><?php echo e($errors->first('role_id')); ?></small>
                            <?php endif; ?>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Only Super Admin can assign roles. Default is User.
                            </small>
                        </div>

                        <div class="form-group col-md-6 mb-4"></div>

                        <div class="form-group col-md-6 mb-4">
                            <label for="photo" class="am-label">
                                <i class="fas fa-camera text-indigo"></i> Profile Photo <small class="text-muted">(Optional)</small>
                            </label>
                            <div class="am-upload-box">
                                <input class="form-control-file" type="file" id="photo" name="photo" accept="image/*">
                                <img id="photoPreview" class="am-preview-img" src="#" alt="Preview">
                            </div>
                            <?php if($errors->has('photo')): ?>
                                <small class="text-danger font-weight-bold"><?php echo e($errors->first('photo')); ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-4">
                            <label for="nid_photo" class="am-label">
                                <i class="fas fa-id-badge text-secondary"></i> NID Photo <small class="text-muted">(Optional)</small>
                            </label>
                            <div class="am-upload-box">
                                <input class="form-control-file" type="file" id="nid_photo" name="nid_photo" accept="image/*">
                                <img id="nidPreview" class="am-preview-img" src="#" alt="Preview">
                            </div>
                            <?php if($errors->has('nid_photo')): ?>
                                <small class="text-danger font-weight-bold"><?php echo e($errors->first('nid_photo')); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center pt-2 border-top">
                        <button class="am-submit-btn" type="submit" id="submit" name="submit">
                            <i class="fas fa-check-circle mr-2"></i> Save Member
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('custom_js'); ?>
<script>
    // SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
    });

    // Pass the CSRF token for post method
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(previewId).attr('src', e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                $(previewId).hide();
            }
        }

        $('#photo').on('change', function() {
            previewImage(this, '#photoPreview');
        });

        $('#nid_photo').on('change', function() {
            previewImage(this, '#nidPreview');
        });

        $('#form').on("submit", function(event) {
            event.preventDefault();
            var form = new FormData(this);
            $.ajax({
                url: "<?php echo e(route('store.member')); ?>",
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                method: "POST",
                success: function(response) {
                    $("#form")[0].reset();
                    $('#photoPreview, #nidPreview').hide();

                    Toast.fire({
                        type: 'success',
                        title: 'Members information successfully saved.'
                    });
                },
                error: function(xhr) {
                    var errorMsg = 'Something Error Found, Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Toast.fire({
                        type: 'error',
                        title: errorMsg
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/member/add-member.blade.php ENDPATH**/ ?>