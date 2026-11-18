

<?php $__env->startSection('heading', 'All Meals'); ?>
<?php $__env->startSection('title', 'view meals'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">View Meal</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="<?php echo e(route('admin.add.meal')); ?>" class="viewall"><i class="fas fa-hamburger"></i> Add Meal</a>

                     <a href="<?php echo e(route('admin.meal.downloadPdf')); ?>" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                </div>
            </div>
                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th> Member's Name</th>
                                <th> Total Meal</th>
                                <th>Month</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            
                         <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($details->full_name); ?></td>
                               
                                <td><?php echo e($details->total_meal); ?></td>
                                <td>
                                    <?php
                                        // Convert short month names to full month names
                                        $monthMap = [
                                            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                        ];
                                        $displayMonth = $monthMap[$details->month] ?? $details->month;
                                    ?>
                                    <?php echo e($displayMonth); ?>

                                </td>
                                <td>
                                    <?php if($details->status == 1): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td style="width: 80px">
                                    <a href="<?php echo e(route('admin.mealDetails',$details->members_id)); ?>" class="btn btn-info btn-xs"> <i class="fas fa-eye"></i> </a>
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
        var base_url = window.location.origin;
            //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });

     
        $(function() {
            $("#all-category").DataTable();
            //   $('#example2').DataTable({
            //     "paging": true,
            //     "lengthChange": false,
            //     "searching": false,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //   });
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/meal/viewMeal.blade.php ENDPATH**/ ?>