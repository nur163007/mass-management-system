

<?php $__env->startSection('heading', 'User Dashboard'); ?>
<?php $__env->startSection('title', 'user-dashboard'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">

                            <h3> <?php echo e($total_meal); ?> </h3>

                            <p>Total Meal</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hamburger"></i>
                        </div>
                        <a href="<?php echo e(route('user.viewMeal')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Tk. <?php echo e(number_format($fund, 2)); ?></h3>

                            <p>Total Payment</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-comment-dollar"></i>
                        </div>
                        <a href="<?php echo e(route('user.viewPayment')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <?php if($meal_due > 0): ?>
                                <h3>Tk <?php echo e(number_format($meal_due, 2)); ?></h3>
                            <?php else: ?>
                                <h3>Tk 0.00</h3>
                            <?php endif; ?>
                            <p>Meal Due</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <a href="<?php echo e(route('user.viewPayment')); ?>" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Tk <?php echo e(number_format($cashback, 2)); ?></h3>
                            <p>Cashback</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-alt"></i>
                        </div>
                        <a href="<?php echo e(route('user.viewPayment')); ?>" class="small-box-footer">More info <i
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

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/userpanel/dashboard/user_dashboard.blade.php ENDPATH**/ ?>