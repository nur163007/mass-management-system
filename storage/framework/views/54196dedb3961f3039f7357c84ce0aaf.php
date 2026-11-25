<?php
    $currentRoleId = session()->get('role', 3);
    $currentMemberId = session()->get('member_id');
    $menuService = app(\App\Services\MenuService::class);
    $sidebarMenus = $menuService->getMenusForRole($currentRoleId, $currentMemberId);
    $dashboardRoute = route('dashboard');
    $profileRoute = route('profile.view');
?>

<aside class="main-sidebar sidebar-light-olive elevation-5">
    <!-- Brand Logo -->
    <a href="<?php echo e($dashboardRoute); ?>" class="brand-link"
        style="display: flex; align-items: center; height: 75px; padding: 10px 16px; border-bottom: 1px solid rgba(0,0,0,0.08); text-decoration: none; overflow: hidden;">
        <img src="<?php echo e(asset('assets/admin/dist/img/mms.png')); ?>" alt="MMS Logo" class="brand-image-logo"
            style="height: 54px; max-height: 56px; max-width: 85px; width: auto; object-fit: contain; flex-shrink: 0; display: inline-block;">
        <span class="brand-text font-weight-bold ml-2 text-dark"
            style="font-size: 1.5rem; letter-spacing: 1.5px; line-height: 1;">MMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-1 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(asset('uploads/members/profile/' . session()->get('profile'))); ?>"
                    class="img-circle elevation-2" alt="User Image"
                    onerror="this.onerror=null;this.src='<?php echo e(asset('assets/admin/dist/img/avatar5.png')); ?>'"
                    style="height:38px;width:38px; border-radius:50%; object-fit: cover;">
            </div>
            <div class="info">
                <a href="<?php echo e($profileRoute); ?>" class="d-block"><?php echo e(session()->get('member_name')); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu"
                data-accordion="false">
                <?php $__currentLoopData = $sidebarMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $hasChildren = $menu->children->isNotEmpty();
                        $isActive = $menu->isActive();
                        $hasActiveChild = $menu->hasActiveChild();
                        $isOpen = $isActive || $hasActiveChild;
                        $menuUrl = $menu->route
                            ? (Route::has($menu->route)
                                ? route($menu->route)
                                : 'javascript:void(0)')
                            : $menu->url ?? 'javascript:void(0)';
                    ?>

                    <?php if($hasChildren): ?>
                        <li class="nav-item has-treeview <?php echo e($isOpen ? 'menu-open' : ''); ?>">
                            <a href="javascript:void(0)" class="nav-link <?php echo e($isOpen ? 'active' : ''); ?>">
                                <i class="nav-icon <?php echo e($menu->icon ?? 'far fa-circle'); ?>"></i>
                                <p>
                                    <?php echo e($menu->name); ?>

                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isChildActive = $child->isActive();
                                        $childUrl = $child->route
                                            ? (Route::has($child->route)
                                                ? route($child->route)
                                                : '#')
                                            : $child->url ?? '#';
                                    ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e($childUrl); ?>"
                                            class="nav-link <?php echo e($isChildActive ? 'active' : ''); ?>">
                                            <i class="<?php echo e($child->icon ?? 'far fa-circle nav-icon'); ?>"></i>
                                            <p><?php echo e($child->name); ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item has-treeview">
                            <a href="<?php echo e($menuUrl); ?>" class="nav-link <?php echo e($isActive ? 'active' : ''); ?>">
                                <i class="nav-icon <?php echo e($menu->icon ?? 'far fa-circle'); ?>"></i>
                                <p><?php echo e($menu->name); ?></p>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/includes/sidebar.blade.php ENDPATH**/ ?>