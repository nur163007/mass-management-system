<div class="col-md-5 text-center col-6 offset-3 mt-2">
    <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success alert-block text-center">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong><?php echo e($message); ?></strong>
        </div>
    <?php endif; ?>

    <?php if(Session::has('error')): ?>
        <div class="alert alert-danger alert-block text-center">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong><?php echo e(Session::get('error')); ?></strong>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/includes/message.blade.php ENDPATH**/ ?>