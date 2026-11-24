

<?php $__env->startSection('heading', 'Summary'); ?>
<?php $__env->startSection('title', 'summaries'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="card">
                
                    <div class="card-header col-md-12 col-12">
                        <h3 class="text-info">Summary</h3>
                    </div>
                
                <div class="col-md-12 mt-3">
                    
                    <div class="row">
                        <div class="col-12 col-sm-4">
                          <div class="info-box bg-light">
                            <div class="info-box-content">
                              <span class="info-box-text text-center text-muted">Total Meal</span>
                              <span class="info-box-number text-center text-muted mb-0"><?php echo e($total_meal); ?></span>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="info-box bg-light">
                            <div class="info-box-content">
                              <span class="info-box-text text-center text-muted">Total Expanses</span>
                              <span class="info-box-number text-center text-muted mb-0">Tk. <?php echo e($total_amount); ?></span>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="info-box bg-light">
                            <div class="info-box-content">
                              <span class="info-box-text text-center text-muted">Total Meal Rate</span>
                              <span class="info-box-number text-center text-muted mb-0">Tk. <?php echo e(number_format($meal_rate ,2)); ?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
            
      
                <hr>
            
                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Member's Name</th>
                                <th>Total Meal</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Dues / Cashback</th>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            
                        <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($total->full_name); ?></td>
                               
                                <td><?php echo e($total->total_meal); ?></td>
                                <td> Tk. <?php echo e(number_format(($total->total_meal * $meal_rate),2)); ?></td>
                                <td>Tk. <?php echo e($total->payment_amount ? $total->payment_amount : '00'); ?></td>
                                <td> Tk. <?php echo e(number_format(($total->payment_amount -($total->total_meal * $meal_rate)),2)); ?></td>
                                <td>
                                    <?php
                                        // Convert short month names to full month names
                                        $monthMap = [
                                            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                        ];
                                        $displayMonth = $monthMap[$total->month] ?? $total->month;
                                    ?>
                                    <?php echo e($displayMonth); ?>

                                </td>

                                <td style="width: 80px">
                                    <a href="<?php echo e(route('admin.memberDetails',$total->id)); ?>" class="btn btn-info btn-xs"> <i class="fa fa-eye"></i> </a>
                                    
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

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/summary/viewSummary.blade.php ENDPATH**/ ?>