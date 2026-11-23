<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuRole;
use App\Models\BillTypeResponsibility;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class MenuService
{
    /**
     * Get menus for a given role ID and member ID
     */
    public function getMenusForRole($roleId, $memberId = null)
    {
        $this->ensureTablesAndSeed();

        $hasBillResponsibility = false;
        if ($memberId) {
            $hasBillResponsibility = BillTypeResponsibility::where('member_id', $memberId)->exists();
        }

        // Query parent menus
        $parentMenus = Menu::whereNull('parent_id')
            ->where('status', 1)
            ->where(function ($query) use ($roleId, $hasBillResponsibility) {
                $query->whereHas('menuRoles', function ($q) use ($roleId) {
                    $q->where('role_id', $roleId);
                });

                if ($hasBillResponsibility) {
                    $query->orWhere('permission_key', 'has_bill_responsibility');
                }
            })
            ->with(['children' => function ($query) use ($roleId, $hasBillResponsibility) {
                $query->where('status', 1)
                    ->where(function ($q) use ($roleId, $hasBillResponsibility) {
                        $q->whereHas('menuRoles', function ($qr) use ($roleId) {
                            $qr->where('role_id', $roleId);
                        });

                        if ($hasBillResponsibility) {
                            $q->orWhere('permission_key', 'has_bill_responsibility');
                        }
                    })
                    ->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get();

        return $parentMenus;
    }

    /**
     * Ensure database tables exist and seed initial menus if empty
     */
    public function ensureTablesAndSeed()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });

            DB::table('roles')->insert([
                [
                    'id' => 1,
                    'name' => 'Super Admin',
                    'slug' => 'super_admin',
                    'description' => 'Full system administrator with complete access',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'Manager',
                    'slug' => 'manager',
                    'description' => 'Mess manager responsible for operations, meals and expenses',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'name' => 'User',
                    'slug' => 'user',
                    'description' => 'Regular mess border / member',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->string('route')->nullable();
                $table->string('url')->nullable();
                $table->string('active_pattern')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('order')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->string('permission_key')->nullable();
                $table->timestamps();

                $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('menu_roles')) {
            Schema::create('menu_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id');
                $table->integer('role_id');
                $table->timestamps();

                $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
                $table->index(['menu_id', 'role_id']);
            });
        }

        // Seed if table is empty
        if (DB::table('menus')->count() == 0) {
            $this->seedInitialMenus();
        } elseif (!cache()->has('system_menus_synced_v4')) {
            $this->ensureSystemMenus();
            cache()->forever('system_menus_synced_v4', true);
        }
    }

    /**
     * Seed initial dynamic sidebar menus (Single Unified Menu System for All Roles)
     */
    public function seedInitialMenus()
    {
        DB::beginTransaction();
        try {
            // 1. Dashboard (Super Admin, Manager, User)
            $this->createMenuWithRoles([
                'name'           => 'Dashboard',
                'icon'           => 'fas fa-tachometer-alt text-secondary',
                'route'          => 'dashboard',
                'active_pattern' => 'dashboard',
                'order'          => 1,
            ], [1, 2, 3]);

            // 2. Manage Members (Super Admin & Manager)
            $membersParent = $this->createMenuWithRoles([
                'name'           => 'Manage Members',
                'icon'           => 'fas fa-users text-success',
                'route'          => null,
                'active_pattern' => 'member/*',
                'order'          => 2,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'Add Member',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.add-member',
                'active_pattern' => 'member/addmembers,member/editdata/*',
                'parent_id'      => $membersParent->id,
                'order'          => 1,
            ], [1]);

            $this->createMenuWithRoles([
                'name'           => 'View Member',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.view-member',
                'active_pattern' => 'member/viewmembers',
                'parent_id'      => $membersParent->id,
                'order'          => 2,
            ], [1, 2]);

            // 3. Room Management (Super Admin & Manager)
            $roomParent = $this->createMenuWithRoles([
                'name'           => 'Room Management',
                'icon'           => 'fas fa-door-open text-info',
                'route'          => null,
                'active_pattern' => 'room*',
                'order'          => 3,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'View Rooms',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.room.index',
                'active_pattern' => 'room',
                'parent_id'      => $roomParent->id,
                'order'          => 1,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'Assign Member',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.room.assign',
                'active_pattern' => 'room/assign',
                'parent_id'      => $roomParent->id,
                'order'          => 2,
            ], [1, 2]);

            // 4. Food Item (Super Admin & Manager)
            $foodItemParent = $this->createMenuWithRoles([
                'name'           => 'Food Item',
                'icon'           => 'fas fa-hotdog text-pink',
                'route'          => null,
                'active_pattern' => 'food/*',
                'order'          => 4,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'Add Food Item',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.add.foodItem',
                'active_pattern' => 'food/addFoodItem,food/editFoodItem/*',
                'parent_id'      => $foodItemParent->id,
                'order'          => 1,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'View Food Item',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.view.foodItem',
                'active_pattern' => 'food/viewFoodItem',
                'parent_id'      => $foodItemParent->id,
                'order'          => 2,
            ], [1, 2]);

            // 5. Manage Expanse (Super Admin, Manager, User)
            $expanseParent = $this->createMenuWithRoles([
                'name'           => 'Manage Expanse',
                'icon'           => 'fas fa-money-bill-alt text-lightblue',
                'route'          => null,
                'active_pattern' => 'expanse/*',
                'order'          => 5,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'Add Food Expanse',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.add.expanse',
                'active_pattern' => 'expanse/addExpanse,expanse/editExpanse/*',
                'parent_id'      => $expanseParent->id,
                'order'          => 1,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'View Food Expanse',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.view.expanse',
                'active_pattern' => 'expanse/viewExpanse,expanse/detailsExpanse/*',
                'parent_id'      => $expanseParent->id,
                'order'          => 2,
            ], [1, 2, 3]);

            // 6. Manage Meal (Super Admin, Manager, User)
            $mealParent = $this->createMenuWithRoles([
                'name'           => 'Manage Meal',
                'icon'           => 'fas fa-hamburger text-orange',
                'route'          => null,
                'active_pattern' => 'meal/*',
                'order'          => 6,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'Add Meal',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.add.meal',
                'active_pattern' => 'meal/addMeal,meal/editMeal/*',
                'parent_id'      => $mealParent->id,
                'order'          => 1,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'View Meal',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.view.meal',
                'active_pattern' => 'meal/viewMeal,meal/mealDetails/*',
                'parent_id'      => $mealParent->id,
                'order'          => 2,
            ], [1, 2, 3]);

            // 7. Manage Payments (Super Admin, Manager, User)
            $paymentParent = $this->createMenuWithRoles([
                'name'           => 'Manage Payments',
                'icon'           => 'fas fa-comment-dollar text-lime',
                'route'          => null,
                'active_pattern' => 'payment/*',
                'order'          => 7,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'Add Payment',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.add.payment',
                'active_pattern' => 'payment/addPayment,payment/editPayment/*',
                'parent_id'      => $paymentParent->id,
                'order'          => 1,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'View Payment',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.view.payment',
                'active_pattern' => 'payment/viewPayment',
                'parent_id'      => $paymentParent->id,
                'order'          => 2,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'My Payment',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'payment.myPayment',
                'active_pattern' => 'payment/myPayment',
                'parent_id'      => $paymentParent->id,
                'order'          => 3,
            ], [1, 2, 3]);

            // 8. Food Advance (Super Admin, Manager, User)
            $this->createMenuWithRoles([
                'name'           => 'Food Advance',
                'icon'           => 'fas fa-hand-holding-usd text-info',
                'route'          => 'admin.foodAdvance.index',
                'active_pattern' => 'foodAdvance*',
                'order'          => 8,
            ], [1, 2, 3]);

            // 9. Bills Management (Super Admin, Manager, User)
            $billParent = $this->createMenuWithRoles([
                'name'           => 'Bills Management',
                'icon'           => 'fas fa-file-invoice-dollar text-warning',
                'route'          => null,
                'active_pattern' => 'bill*',
                'order'          => 9,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'View Bills',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.bill.index',
                'active_pattern' => 'bill',
                'parent_id'      => $billParent->id,
                'order'          => 1,
            ], [1, 2, 3]);

            $this->createMenuWithRoles([
                'name'           => 'Add Bill',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.bill.create',
                'active_pattern' => 'bill/create,bill/*/edit',
                'parent_id'      => $billParent->id,
                'order'          => 2,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'Bill Responsibilities',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.billResponsibility.index',
                'active_pattern' => 'bill-responsibility*',
                'parent_id'      => $billParent->id,
                'order'          => 3,
            ], [1, 2]);

            // 10. Financial Management (Super Admin & Manager)
            $financeParent = $this->createMenuWithRoles([
                'name'           => 'Financial Management',
                'icon'           => 'fas fa-wallet text-danger',
                'route'          => null,
                'active_pattern' => 'servicecharge*,extra-payment*',
                'order'          => 10,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'Service Charges',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.servicecharge.index',
                'active_pattern' => 'servicecharge',
                'parent_id'      => $financeParent->id,
                'order'          => 1,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'Service Expenses',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.servicecharge.expenses',
                'active_pattern' => 'servicecharge/expenses',
                'parent_id'      => $financeParent->id,
                'order'          => 2,
            ], [1, 2]);

            $this->createMenuWithRoles([
                'name'           => 'Extra Payments',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.extra_payment.index',
                'active_pattern' => 'extra-payment*',
                'parent_id'      => $financeParent->id,
                'order'          => 3,
            ], [1, 2]);

            // 11. Meal Summary (Super Admin & Manager)
            $this->createMenuWithRoles([
                'name'           => 'Meal Summary',
                'icon'           => 'fas fa-chart-bar text-teal',
                'route'          => 'admin.total.summary',
                'active_pattern' => 'totalSummary,memberDetails/*',
                'order'          => 11,
            ], [1, 2]);

            // 12. Meal rate Calculate / Reports (Super Admin, Manager, User)
            $this->createMenuWithRoles([
                'name'           => 'Reports',
                'icon'           => 'fas fa-database text-purple',
                'route'          => 'admin.total.report',
                'active_pattern' => 'totalReports,viewReports',
                'order'          => 12,
            ], [1, 2, 3]);

            // 13. Profile (Super Admin, Manager, User)
            $this->createMenuWithRoles([
                'name'           => 'My Profile',
                'icon'           => 'fas fa-user-circle text-secondary',
                'route'          => 'profile.view',
                'active_pattern' => 'profile/*',
                'order'          => 13,
            ], [1, 2, 3]);

            // 14. Role & Menu Management (Super Admin only: role_id 1)
            $roleMenuParent = $this->createMenuWithRoles([
                'name'           => 'Role & Menu',
                'icon'           => 'fas fa-cogs text-secondary',
                'route'          => null,
                'active_pattern' => 'role*,menu*',
                'order'          => 14,
            ], [1]);

            $this->createMenuWithRoles([
                'name'           => 'User Roles',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.role.index',
                'active_pattern' => 'role*',
                'parent_id'      => $roleMenuParent->id,
                'order'          => 1,
            ], [1]);

            $this->createMenuWithRoles([
                'name'           => 'Sidebar Menus',
                'icon'           => 'far fa-circle nav-icon',
                'route'          => 'admin.menu.index',
                'active_pattern' => 'menu*',
                'parent_id'      => $roleMenuParent->id,
                'order'          => 2,
            ], [1]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Ensure system menus exist, remove old duplicate menus, and unify all routes
     */
    public function ensureSystemMenus()
    {
        DB::beginTransaction();
        try {
            // ----------------------------------------------------------------
            // 1. Remove obsolete duplicate user-only menus from old seedings
            // ----------------------------------------------------------------
            $obsoleteRoutes = [
                'user.viewProfile',
                'user.viewMeal',
                'user.addMeal',
                'user.viewPayment',
                'user.addPayment',
                'user.viewExpanse',
                'user.foodAdvance.index',
                'user.bill.index',
                'user.viewReport',
            ];

            // Delete menus with obsolete routes
            $obsoleteMenuIds = DB::table('menus')->whereIn('route', $obsoleteRoutes)->pluck('id');
            if ($obsoleteMenuIds->isNotEmpty()) {
                DB::table('menu_roles')->whereIn('menu_id', $obsoleteMenuIds)->delete();
                DB::table('menus')->whereIn('id', $obsoleteMenuIds)->delete();
            }

            // Delete duplicate user-only parent menus
            $duplicateParents = DB::table('menus')
                ->whereIn('name', ['View Profile', 'View Meals', 'My Payments', 'View Food Expanse', 'Bills', 'Payment Management'])
                ->whereNull('parent_id')
                ->pluck('id');
            if ($duplicateParents->isNotEmpty()) {
                DB::table('menu_roles')->whereIn('menu_id', $duplicateParents)->delete();
                DB::table('menus')->whereIn('id', $duplicateParents)->delete();
            }

            // Delete duplicate Dashboard or Food Advance menus (keep the lowest ID)
            $this->deduplicateMenu('Dashboard');
            $this->deduplicateMenu('Food Advance');

            // ----------------------------------------------------------------
            // 2. Update routes and active_patterns to remove admin/* and user/*
            // ----------------------------------------------------------------
            $updates = [
                ['route_old' => 'admin.dashboard',           'route_new' => 'dashboard',                 'pattern' => 'dashboard'],
                ['route_old' => 'user.dashboard',            'route_new' => 'dashboard',                 'pattern' => 'dashboard'],
                ['route_old' => 'admin.view.profile',        'route_new' => 'profile.view',              'pattern' => 'profile/*'],
                ['route_old' => 'admin.add-member',          'route_new' => 'admin.add-member',          'pattern' => 'member/addmembers,member/editdata/*'],
                ['route_old' => 'admin.view-member',         'route_new' => 'admin.view-member',         'pattern' => 'member/viewmembers'],
                ['route_old' => 'admin.room.index',          'route_new' => 'admin.room.index',          'pattern' => 'room'],
                ['route_old' => 'admin.room.assign',         'route_new' => 'admin.room.assign',         'pattern' => 'room/assign'],
                ['route_old' => 'admin.add.foodItem',        'route_new' => 'admin.add.foodItem',        'pattern' => 'food/addFoodItem,food/editFoodItem/*'],
                ['route_old' => 'admin.view.foodItem',       'route_new' => 'admin.view.foodItem',       'pattern' => 'food/viewFoodItem'],
                ['route_old' => 'admin.add.meal',            'route_new' => 'admin.add.meal',            'pattern' => 'meal/addMeal,meal/editMeal/*'],
                ['route_old' => 'admin.view.meal',           'route_new' => 'admin.view.meal',           'pattern' => 'meal/viewMeal,meal/mealDetails/*'],
                ['route_old' => 'admin.add.payment',         'route_new' => 'admin.add.payment',         'pattern' => 'payment/addPayment,payment/editPayment/*'],
                ['route_old' => 'admin.view.payment',        'route_new' => 'admin.view.payment',        'pattern' => 'payment/viewPayment'],
                ['route_old' => 'admin.view.expanse',        'route_new' => 'admin.view.expanse',        'pattern' => 'expanse/viewExpanse,expanse/detailsExpanse/*'],
                ['route_old' => 'admin.add.expanse',         'route_new' => 'admin.add.expanse',         'pattern' => 'expanse/addExpanse,expanse/editExpanse/*'],
                ['route_old' => 'admin.foodAdvance.index',   'route_new' => 'admin.foodAdvance.index',   'pattern' => 'foodAdvance*'],
                ['route_old' => 'admin.bill.index',          'route_new' => 'admin.bill.index',          'pattern' => 'bill'],
                ['route_old' => 'admin.bill.create',         'route_new' => 'admin.bill.create',         'pattern' => 'bill/create,bill/*/edit'],
                ['route_old' => 'admin.billResponsibility.index', 'route_new' => 'admin.billResponsibility.index', 'pattern' => 'bill-responsibility*'],
                ['route_old' => 'admin.total.report',        'route_new' => 'admin.total.report',        'pattern' => 'totalReports,viewReports'],
                ['route_old' => 'admin.total.summary',       'route_new' => 'admin.total.summary',       'pattern' => 'totalSummary,memberDetails/*'],
                ['route_old' => 'admin.servicecharge.index', 'route_new' => 'admin.servicecharge.index', 'pattern' => 'servicecharge'],
                ['route_old' => 'admin.servicecharge.expenses', 'route_new' => 'admin.servicecharge.expenses', 'pattern' => 'servicecharge/expenses'],
                ['route_old' => 'admin.extra_payment.index', 'route_new' => 'admin.extra_payment.index', 'pattern' => 'extra-payment*'],
                ['route_old' => 'admin.role.index',          'route_new' => 'admin.role.index',          'pattern' => 'role*'],
                ['route_old' => 'admin.menu.index',          'route_new' => 'admin.menu.index',          'pattern' => 'menu*'],
            ];

            foreach ($updates as $u) {
                DB::table('menus')->where('route', $u['route_old'])->update([
                    'route'          => $u['route_new'],
                    'active_pattern' => $u['pattern'],
                ]);
            }

            // Strip any remaining admin/ or user/ in active_patterns
            DB::table('menus')
                ->where('active_pattern', 'like', 'admin/%')
                ->orWhere('active_pattern', 'like', 'user/%')
                ->get()
                ->each(function ($menu) {
                    $fixed = preg_replace('/\b(admin|user)\//', '', $menu->active_pattern);
                    DB::table('menus')->where('id', $menu->id)->update(['active_pattern' => $fixed]);
                });

            // ----------------------------------------------------------------
            // 3. Attach User role (role_id = 3) to all shared unified menus
            // ----------------------------------------------------------------
            $sharedMenuNames = [
                'Dashboard',
                'Manage Expanse',
                'Add Food Expanse',
                'View Food Expanse',
                'Manage Meal',
                'Add Meal',
                'View Meal',
                'Manage Payments',
                'Add Payment',
                'View Payment',
                'My Payment',
                'Food Advance',
                'Bills Management',
                'View Bills',
                'Meal rate Calculate',
                'Reports',
                'My Profile',
            ];

            $sharedMenus = DB::table('menus')->whereIn('name', $sharedMenuNames)->get();
            foreach ($sharedMenus as $sm) {
                $hasUserRole = DB::table('menu_roles')
                    ->where('menu_id', $sm->id)
                    ->where('role_id', 3)
                    ->exists();

                if (!$hasUserRole) {
                    DB::table('menu_roles')->insert([
                        'menu_id'    => $sm->id,
                        'role_id'    => 3,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // ----------------------------------------------------------------
            // 4. Ensure My Payment child menu exists under Manage Payments
            // ----------------------------------------------------------------
            $paymentParent = Menu::where(function ($q) {
                $q->where('name', 'Manage Payments')->orWhere('name', 'Manage Payment');
            })->whereNull('parent_id')->first();

            if ($paymentParent) {
                $myPaymentMenu = Menu::where('route', 'payment.myPayment')->first();
                if (!$myPaymentMenu) {
                    $this->createMenuWithRoles([
                        'name'           => 'My Payment',
                        'icon'           => 'far fa-circle nav-icon',
                        'route'          => 'payment.myPayment',
                        'active_pattern' => 'payment/myPayment',
                        'parent_id'      => $paymentParent->id,
                        'order'          => 3,
                    ], [1, 2, 3]);
                } else {
                    $myPaymentMenu->update([
                        'parent_id'      => $paymentParent->id,
                        'name'           => 'My Payment',
                        'status'         => 1,
                        'active_pattern' => 'payment/myPayment',
                    ]);
                    foreach ([1, 2, 3] as $rId) {
                        if (!DB::table('menu_roles')->where('menu_id', $myPaymentMenu->id)->where('role_id', $rId)->exists()) {
                            DB::table('menu_roles')->insert([
                                'menu_id'    => $myPaymentMenu->id,
                                'role_id'    => $rId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // ----------------------------------------------------------------
            // 4. Ensure My Profile menu item exists
            // ----------------------------------------------------------------
            $hasProfile = DB::table('menus')->where('route', 'profile.view')->exists();
            if (!$hasProfile) {
                $this->createMenuWithRoles([
                    'name'           => 'My Profile',
                    'icon'           => 'fas fa-user-circle text-secondary',
                    'route'          => 'profile.view',
                    'active_pattern' => 'profile/*',
                    'order'          => 13,
                ], [1, 2, 3]);
            }

            // ----------------------------------------------------------------
            // 5. Ensure Role & Menu parent exists for Super Admin
            // ----------------------------------------------------------------
            $hasRoleMenu = DB::table('menus')->where('route', 'admin.role.index')->exists();
            if (!$hasRoleMenu) {
                $parent = Menu::where('name', 'Role & Menu')->first();
                if (!$parent) {
                    $parent = $this->createMenuWithRoles([
                        'name'           => 'Role & Menu',
                        'icon'           => 'fas fa-cogs text-secondary',
                        'route'          => null,
                        'active_pattern' => 'role*,menu*',
                        'order'          => 14,
                    ], [1]);
                }

                if (!DB::table('menus')->where('route', 'admin.role.index')->exists()) {
                    $this->createMenuWithRoles([
                        'name'           => 'User Roles',
                        'icon'           => 'far fa-circle nav-icon',
                        'route'          => 'admin.role.index',
                        'active_pattern' => 'role*',
                        'parent_id'      => $parent->id,
                        'order'          => 1,
                    ], [1]);
                }

                if (!DB::table('menus')->where('route', 'admin.menu.index')->exists()) {
                    $this->createMenuWithRoles([
                        'name'           => 'Sidebar Menus',
                        'icon'           => 'far fa-circle nav-icon',
                        'route'          => 'admin.menu.index',
                        'active_pattern' => 'menu*',
                        'parent_id'      => $parent->id,
                        'order'          => 2,
                    ], [1]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Deduplicate a menu by name (keeps the one with lowest id)
     */
    protected function deduplicateMenu($name)
    {
        $menus = DB::table('menus')->where('name', $name)->orderBy('id', 'asc')->get();
        if ($menus->count() > 1) {
            $keepId = $menus->first()->id;
            $removeIds = $menus->slice(1)->pluck('id');
            DB::table('menu_roles')->whereIn('menu_id', $removeIds)->delete();
            DB::table('menus')->whereIn('id', $removeIds)->delete();
        }
    }

    /**
     * Helper to create menu and attach roles
     */
    protected function createMenuWithRoles(array $menuData, array $roles)
    {
        $menu = Menu::create($menuData);
        foreach ($roles as $roleId) {
            MenuRole::create([
                'menu_id' => $menu->id,
                'role_id' => $roleId,
            ]);
        }
        return $menu;
    }
}
