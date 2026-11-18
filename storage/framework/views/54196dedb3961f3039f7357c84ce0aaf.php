<aside class="main-sidebar sidebar-light-olive elevation-5">
    <!-- Brand Logo -->
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="mb-3">
        <img src="<?php echo e(asset('assets/admin/dist/img/projectlogo.PNG')); ?>" alt="MMS"
            class="brand-image img-square elevation-0 ml-4" style="opacity: .8">

    </a>
    <hr>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-1 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(asset('uploads/members/profile/' . session()->get('profile'))); ?>"
                    class="img-circle elevation-2" alt="User Image" style="height:38px;width:38px; border-radius:50%;">
            </div>
            <div class="info">
                <a href="<?php echo e(route('admin.view.profile')); ?>" class="d-block"><?php echo e(session()->get('member_name')); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview">
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/dashboard') ? 'active' : ''); ?> ">
                        <i class="nav-icon fas fa-tachometer-alt text-secondary"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/member/*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-users text-success"></i>
                        <p>
                            Manage Members
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.add-member')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/member/addmembers', 'admin/member/editdata/*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Member</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.view-member')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/member/viewmembers') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Member</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/room*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-door-open text-info"></i>
                        <p>
                            Room Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.room.index')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/room') && !request()->is('admin/room/assign') && !request()->is('admin/room/*/edit') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Rooms</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.room.assign')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/room/assign') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Assign Member</p>
                            </a>
                        </li>
                    </ul>
                </li>
                

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/food/*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-hotdog text-pink"></i>
                        <p>
                            Food Item
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.add.foodItem')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/food/addFoodItem', 'admin/food/editFoodItem/*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Food Item</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.view.foodItem')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/food/viewFoodItem') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Food Item</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/expanse/*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-alt text-lightblue"></i>
                        <p>
                            Manage Expanse
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.add.expanse')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/expanse/addExpanse', 'admin/expanse/editExpanse/*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Food Expanse</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.view.expanse')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/expanse/viewExpanse', 'admin/expanse/detailsExpanse/*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Food Expanse</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/meal/*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-hamburger text-orange"></i>
                        <p>
                            Manage Meal
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.add.meal')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/meal/addMeal', 'admin/meal/editMeal/*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Meal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.view.meal')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/meal/viewMeal', 'admin/meal/mealDetails/*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Meal</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/payment/*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-comment-dollar text-lime"></i>
                        <p>
                            Manage Payments
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.add.payment')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/payment/addPayment', 'admin/payment/editPayment/*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Payment</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.view.payment')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/payment/viewPayment') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Payment</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('user.viewPayment')); ?>"
                                class="nav-link <?php echo e(request()->is('user/payment/viewPayment') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>My Payments</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/foodAdvance/*') ? 'menu-open' : ''); ?>">
                    <a href="<?php echo e(route('admin.foodAdvance.index')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/foodAdvance*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-hand-holding-usd text-info"></i>
                        <p>
                            Food Advance
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview <?php echo e(request()->is('admin/bill*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar text-warning"></i>
                        <p>
                            Bills Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.bill.index')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/bill') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Bills</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.bill.create')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/bill/create', 'admin/bill/*/edit') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Bill</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.billResponsibility.index')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/bill-responsibility*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bill Responsibilities</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nav-item has-treeview <?php echo e(request()->is('admin/servicecharge*') || request()->is('admin/extra-payment*') ? 'menu-open' : ''); ?>">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-wallet text-danger"></i>
                        <p>
                            Financial Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.servicecharge.index')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/servicecharge') && !request()->is('admin/servicecharge/expenses') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Charges</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.servicecharge.expenses')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/servicecharge/expenses') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Expenses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.extra_payment.index')); ?>"
                                class="nav-link <?php echo e(request()->is('admin/extra-payment*') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Extra Payments</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="<?php echo e(route('admin.total.summary')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/totalSummary', 'admin/memberDetails/*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-chart-bar text-teal"></i>
                        <p>
                            Meal Summary
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="<?php echo e(route('admin.total.report')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/totalReports', 'admin/viewReports') ? 'active' : ''); ?> ">
                        <i class="nav-icon fas fa-database text-purple"></i>
                        <p>
                            Meal rate Calculate
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/includes/sidebar.blade.php ENDPATH**/ ?>