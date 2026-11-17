

<?php $__env->startSection('heading', 'View Rooms'); ?>
<?php $__env->startSection('title', 'view rooms'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Rooms</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="<?php echo e(route('admin.room.assign')); ?>" class="viewall"><i class="fas fa-user-plus"></i> Assign Member</a>
                    </div>
                </div>

                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title font-weight-bold"><?php echo e($room->room_name); ?></h3>
                                    <div class="card-tools">
                                        <span class="badge badge-info">Rent: Tk. <?php echo e(number_format($room->monthly_rent, 2)); ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted"><strong>Capacity:</strong> <?php echo e($room->capacity); ?> members</p>
                                    <p class="text-muted"><strong>Current Members:</strong> <?php echo e($room->roomMembers->count()); ?> / <?php echo e($room->capacity); ?></p>
                                    
                                    <?php if($room->roomMembers->count() > 0): ?>
                                    <hr>
                                    <h6 class="font-weight-bold">Members:</h6>
                                    <ul class="list-unstyled">
                                        <?php $__currentLoopData = $room->roomMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="fas fa-user"></i> <?php echo e($roomMember->member->full_name); ?>

                                                    <?php if($roomMember->member->role_id == 2): ?>
                                                        <span class="badge badge-warning badge-sm">Manager</span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="badge badge-success">Rent: Tk. <?php echo e(number_format($roomMember->monthly_rent, 2)); ?></span>
                                            </div>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <?php else: ?>
                                    <p class="text-muted text-center">No members assigned</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <a href="<?php echo e(route('admin.room.show', $room->id)); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-info-circle"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($rooms->count() == 0): ?>
                    <div class="alert alert-info text-center">
                        <h5>No rooms found</h5>
                        <p>Please initialize rooms first.</p>
                        <a href="<?php echo e(route('admin.room.initialize')); ?>" class="btn btn-primary">Initialize Rooms</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/room/index.blade.php ENDPATH**/ ?>