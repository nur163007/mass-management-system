

<?php $__env->startSection('heading', 'Bills Management'); ?>
<?php $__env->startSection('title', 'view bills'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Bills</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="<?php echo e(route('admin.bill.create')); ?>" class="viewall"><i class="fas fa-plus"></i> Add Bill</a>
                    </div>
                </div>

                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="<?php echo e(route('admin.bill.index')); ?>">
                                <div class="input-group">
                                    <select name="month" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Month</option>
                                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($m); ?>" <?php echo e($month == $m ? 'selected' : ''); ?>>
                                                <?php echo e($m); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Bill Type</th>
                                <th>Total Amount</th>
                                <th>Per Person</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <?php if($bill->bill_type == 'water'): ?>
                                        <span class="badge badge-primary">Water Bill</span>
                                    <?php elseif($bill->bill_type == 'internet'): ?>
                                        <span class="badge badge-info">Internet Bill</span>
                                    <?php elseif($bill->bill_type == 'electricity'): ?>
                                        <span class="badge badge-warning">Electricity Bill</span>
                                    <?php elseif($bill->bill_type == 'gas'): ?>
                                        <span class="badge badge-danger">Gas Bill</span>
                                    <?php elseif($bill->bill_type == 'bua_moyla' || $bill->bill_type == 'bua' || $bill->bill_type == 'moyla'): ?>
                                        <span class="badge badge-secondary">Bua & Moyla Bill</span>
                                    <?php else: ?>
                                        <span class="badge badge-default"><?php echo e(ucfirst($bill->bill_type)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>Tk. <?php echo e(number_format($bill->total_amount, 2)); ?></td>
                                <td>Tk. <?php echo e(number_format($bill->per_person_amount, 2)); ?></td>
                                <td style="width: 150px">
                                    <a href="<?php echo e(route('admin.bill.view', $bill->id)); ?>" class="btn btn-success btn-xs" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.bill.edit', $bill->id)); ?>" class="btn btn-info btn-xs" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.bill.delete', $bill->id)); ?>" 
                                       class="btn btn-danger btn-xs" 
                                       onclick="return confirm('Are you sure you want to delete this bill?')"
                                       title="Delete">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">No bills found for this month.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
<script>
    $(function() {
        $("#all-category").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        });
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/bill/index.blade.php ENDPATH**/ ?>