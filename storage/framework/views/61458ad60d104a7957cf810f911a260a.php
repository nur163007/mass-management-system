

<?php $__env->startSection('heading', 'Service Charge Expenses'); ?>
<?php $__env->startSection('title', 'service charge expenses'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Service Charge Expenses</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="<?php echo e(route('admin.servicecharge.index')); ?>" class="viewall"><i class="fas fa-arrow-left"></i> Back to Service Charge</a>
                    </div>
                </div>

                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="<?php echo e(route('admin.servicecharge.expenses')); ?>">
                                <div class="input-group">
                                    <select name="month" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Month</option>
                                        <?php
                                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                            $currentMonth = \Carbon\Carbon::now()->format('F');
                                        ?>
                                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($m); ?>" <?php echo e(($month ?? $currentMonth) == $m ? 'selected' : ''); ?>>
                                                <?php echo e($m); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Collected</span>
                                    <span class="info-box-number">Tk. <?php echo e(number_format($totalCollected, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Spent</span>
                                    <span class="info-box-number">Tk. <?php echo e(number_format($totalSpent, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Balance</span>
                                    <span class="info-box-number">Tk. <?php echo e(number_format($balance, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title font-weight-bold">Add Service Expense</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('admin.servicecharge.expense.store')); ?>" method="POST" id="form">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="expense_type">Expense Type</label>
                                        <input type="text" class="form-control" id="expense_type" name="expense_type" placeholder="e.g., House Maintenance, Repair" required autocomplete="off">
                                        <?php if($errors->has('expense_type')): ?>
                                            <p class="text-danger"><?php echo e($errors->first('expense_type')); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="amount">Amount (Tk.)</label>
                                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter expense amount" step="0.01" min="0" required autocomplete="off">
                                        <?php if($errors->has('amount')): ?>
                                            <p class="text-danger"><?php echo e($errors->first('amount')); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="expense_date">Expense Date</label>
                                        <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?php echo e(date('Y-m-d')); ?>" required autocomplete="off">
                                        <?php if($errors->has('expense_date')): ?>
                                            <p class="text-danger"><?php echo e($errors->first('expense_date')); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="month">Month</label>
                                        <select class="form-control" id="month" name="month" required autocomplete="off">
                                            <option value="">--Select Month--</option>
                                            <?php
                                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                                $currentMonth = \Carbon\Carbon::now()->format('F');
                                            ?>
                                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($m); ?>" <?php echo e(($month ?? $currentMonth) == $m ? 'selected' : ''); ?>>
                                                    <?php echo e($m); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if($errors->has('month')): ?>
                                            <p class="text-danger"><?php echo e($errors->first('month')); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group col-md-8">
                                        <label for="description">Description (Optional)</label>
                                        <textarea class="form-control" id="description" name="description" rows="2" placeholder="Add description about this expense" autocomplete="off"></textarea>
                                        <?php if($errors->has('description')): ?>
                                            <p class="text-danger"><?php echo e($errors->first('description')); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Add Expense</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-info">
                            <h3 class="card-title font-weight-bold">Expense History</h3>
                        </div>
                        <div class="card-body">
                            <?php if($expenses->count() > 0): ?>
                            <table class="table table-bordered table-hover">
                                <thead class="bg-cyan">
                                    <tr>
                                        <th>SL NO</th>
                                        <th>Expense Type</th>
                                        <th>Amount</th>
                                        <th>Expense Date</th>
                                        <th>Month</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($expense->expense_type); ?></td>
                                        <td>Tk. <?php echo e(number_format($expense->amount, 2)); ?></td>
                                        <td><?php echo e($expense->expense_date ? date('d M Y', strtotime($expense->expense_date)) : 'N/A'); ?></td>
                                        <td><?php echo e($expense->month); ?></td>
                                        <td><?php echo e($expense->description ?? '-'); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.servicecharge.expense.delete', $expense->id)); ?>" 
                                               class="btn btn-danger btn-xs" 
                                               onclick="return confirm('Are you sure you want to delete this expense?')">
                                                <i class="fa fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="alert alert-info text-center">
                                <h5>No expenses recorded for this month</h5>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
<script>
    //  SweetAlert2 
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
    });

    // pass the csrf token for post method.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('table').DataTable();

        $('#form').on("submit", function(event) {
            event.preventDefault();
            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                method: "POST",
                success: function(response) {
                    Toast.fire({
                        type: 'success',
                        title: 'Expense added successfully.'
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    var errorMsg = 'Something Error Found, Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
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


<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/servicecharge/expenses.blade.php ENDPATH**/ ?>