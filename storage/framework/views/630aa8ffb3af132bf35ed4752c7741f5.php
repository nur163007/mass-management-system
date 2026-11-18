

<?php $__env->startSection('heading', 'dashboard'); ?>
<?php $__env->startSection('title', 'dashboard'); ?>

<?php $__env->startSection('custom_css'); ?>
<style>
/* Mobile: small-box card text responsive fix */
@media (max-width: 767px) {
    .small-box > .inner h3 {
        font-size: 1.3rem !important;
        word-break: break-word;
        line-height: 1.2;
    }
    .small-box > .inner p {
        font-size: 0.75rem !important;
        white-space: normal;
        word-break: break-word;
    }
    .small-box .icon {
        display: none; /* icon লুকাও, জায়গা বাড়বে */
    }
    .small-box > .inner {
        padding: 8px 10px !important;
    }
    .small-box-footer {
        font-size: 0.75rem;
    }
}
/* Extra small screens (< 400px) */
@media (max-width: 400px) {
    .small-box > .inner h3 {
        font-size: 1rem !important;
    }
    .small-box > .inner p {
        font-size: 0.65rem !important;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <h3><?php echo e($member->total_member); ?></h3>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <p>Total Members</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="<?php echo e(route('admin.view-member')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-olive">
                        <div class="inner">

                            <h3><?php echo e($total_meal); ?></h3>

                            <p>Total Meal</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hamburger"></i>
                        </div>
                        <a href="<?php echo e(route('admin.view.meal')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Tk <?php echo e(number_format($meal_rate, 2)); ?></h3>

                            <p>Meal Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="<?php echo e(route('admin.view.meal')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Tk. <?php echo e($fund); ?></h3>

                            <p>Total Payment</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <a href="<?php echo e(route('admin.view.payment')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3>Tk. <?php echo e($all_ex); ?></h3>

                            <p>Total Expanse</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <a href="<?php echo e(route('admin.view.expanse')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-navy">
                        <div class="inner">
                            <?php if($cash < 0): ?>
                                <h3>Tk <?php echo e(number_format($cash, 2)); ?></h3>
                            <?php else: ?>
                                <h3>Tk 0.00</h3>
                            <?php endif; ?>
                            <p>Food Expanses Due</p>
                        </div>
                        <div class="icon text-dark">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <a href="<?php echo e(route('admin.view.expanse')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>Tk. <?php echo e(number_format($remaining, 2)); ?></h3>

                            <p>Unpaid Bills</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-alt"></i>
                        </div>
                        <a href="<?php echo e(route('admin.view.payment')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>