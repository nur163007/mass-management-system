

<?php $__env->startSection('heading', 'All Expanses'); ?>
<?php $__env->startSection('title', 'expanse details'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Expanse details</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="<?php echo e(route('admin.view.expanse')); ?>" class="viewall"><i class="far fa-money-bill-alt"></i> All Expanse</a>
                    </div>
                </div>
                <div class="col-md-12 mt-3 ml-2">
                    <h4 class="text-info">Member's Info.</h4>
                    <div class="card">
                        <div class="row">
                        <div class="col-md-2 mr-2 ml-2">
                    <img style="width: 180px; height:200px;padding:5px;"
                                            src="<?php echo e(asset('uploads/members/profile/' . $members->photo)); ?>" alt="">
                </div>
                <div class="col-md-9 mt-1">

                    <table class="table table-active">
                          
                        <tbody>
                            <tr>
                                <th style="width: 20%">Name</th>
                                <td><?php echo e($members->full_name); ?></td>
                            </tr>  
                            <tr>
                                <th style="width: 20%">Phone</th>
                                <td>0<?php echo e($members->phone_no); ?></td>
                            </tr>  
                            <tr>
                                <th style="width: 20%">Total Expanses</th>
                                
                                <td> Tk.<?php echo e($members->total_amount); ?></td>
                              
                            </tr>  
                            <tr>
                                <th style="width: 20%">Expanse's Date</th>
                                <td> <?php echo e(date('d M, Y',strtotime($members->date))); ?></td>
                                
                            </tr>               
                            
                       
                        </tbody>
                    </table>
               
            </div>
        </div>
            </div>
            </div>
            
            <div class="col-md-12 mt-3 ml-2">
                <hr>
                <h3 class="text-success">Item Details</h3>
              
            </div>

                

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Item Name</th>
                               
                                <th>Description</th>
                                <th>Weight</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            
                        <?php $__currentLoopData = $all_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($details->item_name); ?></td>
                               
                                <td><?php echo e($details->item_description); ?></td>
                                <td><?php echo e($details->weight); ?></td>
                                <td> Tk.<?php echo e($details->amount); ?></td>

                                <td style="width: 80px">
                                    <a href="<?php echo e(route('edit.expanse',$details->id)); ?>" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                    
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

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/expanse/detailExpanse.blade.php ENDPATH**/ ?>