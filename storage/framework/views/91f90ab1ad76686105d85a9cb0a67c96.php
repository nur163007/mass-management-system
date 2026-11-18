<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $__env->make('admin.includes.stylesheet', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('custom_css'); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php if(session()->get('role') == 1 || session()->get('role') == 2): ?>
            <?php echo $__env->make('admin.includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif(session()->get('role') == 3): ?>
            <?php echo $__env->make('userpanel.includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php if(session()->get('role') == 1 || session()->get('role') == 2): ?>
            <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif(session()->get('role') == 3): ?>
            <?php echo $__env->make('userpanel.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>




        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <ol class="breadcrumb float-sm-right mb-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active d-none d-sm-inline"><?php echo $__env->yieldContent('heading'); ?></li>
                                <li class="breadcrumb-item active d-inline d-sm-none text-truncate"
                                    style="max-width: 55vw;"><?php echo $__env->yieldContent('heading'); ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <?php echo $__env->yieldContent('main-content'); ?>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong> &copy; 2026 mms.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b> Developed by <a href="https://nur163007.github.io/" target="_blank">Nur</a></b>
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <?php echo $__env->make('admin.includes.stylejs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/master.blade.php ENDPATH**/ ?>