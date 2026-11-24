

<?php $__env->startSection('heading', 'All Expanses'); ?>
<?php $__env->startSection('title', 'view expanse'); ?>

<?php $__env->startSection('main-content'); ?> 
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Expanses</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="<?php echo e(route('admin.add.expanse')); ?>" class="viewall"><i class="far fa-money-bill-alt"></i> Add Expanse</a>
                         <a href="<?php echo e(route('admin.expanse.downloadPdf', ['month' => $month ?? ''])); ?>" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                    </div>
                </div>

                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <!-- Month Filter -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-5 col-sm-7 col-12">
                            <form method="GET" action="<?php echo e(route('admin.view.expanse')); ?>" id="monthFilterForm">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-olive text-white font-weight-bold">
                                            <i class="fas fa-calendar-alt mr-1"></i> Month
                                        </span>
                                    </div>
                                    <select name="month" id="monthFilter" class="form-control" onchange="document.getElementById('monthFilterForm').submit()">
                                        <option value="">All Months</option>
                                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($m); ?>" <?php echo e((isset($month) && $month == $m) ? 'selected' : ''); ?>>
                                                <?php echo e($m); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if(!empty($month)): ?>
                                        <div class="input-group-append">
                                            <a href="<?php echo e(route('admin.view.expanse')); ?>" class="btn btn-outline-secondary" title="Reset filter to all months">
                                                <i class="fas fa-times"></i> Clear
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-7 col-sm-5 col-12 text-md-right mt-2 mt-md-0">
                            <?php if(!empty($month)): ?>
                                <span class="badge badge-info p-2" style="font-size: 13px;">
                                    <i class="fas fa-filter"></i> Filtered by: <strong><?php echo e($month); ?></strong>
                                </span>
                            <?php endif; ?>
                            <span class="badge badge-success p-2 ml-1" style="font-size: 13px;">
                                Total Amount: <strong>Tk. <?php echo e(number_format($totalAmount ?? 0, 2)); ?></strong>
                            </span>
                        </div>
                    </div>
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th style="white-space: nowrap;">SL NO</th>
                                <th style="white-space: nowrap;">Member's Name</th>
                                <th style="white-space: nowrap;">Total Amount</th>
                                <th style="white-space: nowrap;">Date</th>
                                <th style="white-space: nowrap;">Status</th>
                                <th style="white-space: nowrap;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            
                        <?php $__currentLoopData = $bazars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="white-space: nowrap;"><?php echo e($loop->iteration); ?></td>
                                <td style="white-space: nowrap;"><?php echo e($item->full_name); ?></td>
                                <td style="white-space: nowrap;">Tk. <?php echo e($item->total); ?></td>
                                <td style="white-space: nowrap;"><?php echo e($item->expanse_date); ?></td>
                                <td style="white-space: nowrap;">
                                    <?php if($item->status == 1): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td style="width: 180px; white-space: nowrap;">
                                    <a href="<?php echo e(route('details.expanse',$item->invoice_no)); ?>" class="btn btn-info btn-xs" title="View" style="border-radius: 5px; padding: 5px 10px;"> 
                                        <i class="fa fa-eye"></i> 
                                    </a>
                                    <?php if($item->status == 0): ?>
                                        <button type="button" class="btn btn-success btn-xs approve-expanse-btn" data-id="<?php echo e($item->id); ?>" title="Approve" style="border-radius: 5px; padding: 5px 10px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.2); cursor: pointer; margin-left: 5px;">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('custom_js'); ?>
    <script>
        $(document).ready(function() {
            $("#all-category").DataTable();

            //  SweetAlert2 Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'success',
                showConfirmbutton: false,
                timer: 3000
            });

            // Use event delegation for approve button
            $(document).on('click', '.approve-expanse-btn', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this expanse?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed || result.value === true) {
                        $.ajax({
                            url: "<?php echo e(url('admin/expanse/expanseStatus')); ?>/" + id + '/1',
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response && response.message == 'Success') {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Expanse successfully approved.',
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: (response && response.message) ? response.message : 'Something Error Found, Please try again.',
                                    });
                                }
                            },
                            error: function(xhr) {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something Error Found, Please try again.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/expanse/viewExpanse.blade.php ENDPATH**/ ?>