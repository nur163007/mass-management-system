

<?php $__env->startSection('heading', 'Add-Item'); ?>
<?php $__env->startSection('title', 'Item'); ?>
<?php $__env->startSection('main-content'); ?>
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Add Item</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="<?php echo e(route('admin.view.foodItem')); ?>" class="viewall"><i class="fas fa-dolly-flatbed"></i> All Items</a>
                </div>
            </div>
            <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                 <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="item_name">Item Name</label>
                            <input class="form-control" type="text" id="item_name" name="item_name" placeholder="Enter Item Name">
                            <?php if($errors->has('item_name')): ?>
                                <p class="text-danger"><?php echo e($errors->first('item_name')); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="item_description">Item Description</label>
                            <textarea rows="3"col="15"class="form-control" type="text" name="item_description"></textarea>
                            <?php if($errors->has('item_description')): ?>
                                <p class="text-danger"><?php echo e($errors->first('item_description')); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="item_photo"> Photos</label>
                            <input class="form-control-file" type="file" id="item_photo" name="item_photo">

                            <?php if($errors->has('item_photo')): ?>
                                <p class="text-danger"><?php echo e($errors->first('item_photo')); ?></p>
                            <?php endif; ?>
                        </div>
                     
                    </div>
                    <input class="btn btn-success" type="submit" id="submit" name="submit" value="submit">
                </form>
            </div>
        </div>

        <!-- /.row -->

    </div><!-- /.container-fluid -->
</section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('custom_js'); ?>

<script>

    //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });


    // pass the csrf token for post method.
       $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

$(document).ready(function(){

    $('#form').on("submit",function(event){
        event.preventDefault();
        var form = new FormData(this);
        $.ajax({
            url:"<?php echo e(route('store.foodItem')); ?>",
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
            //    alert(response);
                // alert('successfully stored');
                $("#form")[0].reset();
               
                Toast.fire({
                            type:'success',
                            title:'Item successfully saved.',
                        });

            //   msg ="<div class='alert alert-dark'>"+response+"</div>";
			// 	      $("#msg").html(msg);
            },
            error:function(error){
                Toast.fire({
                            type:'error',
                            title:'Something Error Found, Please try again.',
                        });
            }
        });
      
    
    });
});


</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/foodItem/addItem.blade.php ENDPATH**/ ?>