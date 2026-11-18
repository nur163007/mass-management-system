

<?php $__env->startSection('heading', 'All Items'); ?>
<?php $__env->startSection('title', 'view item'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">View Items</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="<?php echo e(route('admin.add.foodItem')); ?>" class="viewall"><i class="fas fa-dolly-flatbed"></i> Add Item</a>
                    </div>
                </div>

                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-cyan">
                            <tr>
                                <th>SL NO</th>
                                <th> Item Name</th>
                                <th> Item Description</th>
                                <th>Item Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            
                        
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($item->item_name); ?></td>
                                <td><?php echo e($item->item_description); ?></td>
                               
                                <td><img style="width: 60px; height:60px"
                                            src="<?php echo e(asset('uploads/foodItems/' . $item->item_photo)); ?>" alt=""></td>
                               
                                <td style="width: 80px">
                                    <a href="<?php echo e(route('admin.editFoodItem',$item->id)); ?>" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                    <a href="<?php echo e(route('admin.deleteFoodItem',$item->id)); ?>" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a>
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

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/foodItem/viewItem.blade.php ENDPATH**/ ?>