<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddmemberController;
use App\Http\Controllers\ExpanseController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SmartDashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Admin\BillResponsibilityController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\ServiceChargeController;
use App\Http\Controllers\MemberExtraPaymentController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\UserMealController;
use App\Http\Controllers\User\UserPaymentController;
use App\Http\Controllers\User\UserExpanseController;
use App\Http\Controllers\User\UserReportController;
use App\Http\Controllers\Admin\FoodAdvanceController;
use App\Http\Controllers\User\FoodAdvanceController as UserFoodAdvanceController;
use App\Http\Controllers\User\UserBillController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All routes use auth.check middleware (login check only).
| Role-based access control is handled inside each controller.
|
*/


//===============================LOGIN & REGISTRATION ROUTES====================================
Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('loginCheck', [LoginController::class, 'loginCheck'])->name('loginCheck');
Route::get('user_logout', [LoginController::class, 'userLogout'])->name('user_logout');
Route::get('register', [UserController::class, 'userRegistration'])->name('register');
Route::get('user_registration', [UserController::class, 'userRegistration'])->name('user.register');
Route::post('member/register', [UserController::class, 'register'])->name('register.user');

//===============================AUTHENTICATED ROUTES====================================
Route::middleware('auth.check')->group(function () {

    //===============================DASHBOARD====================================
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //===============================SMART DASHBOARD (Admin/Manager)====================================
    Route::get('smart/analytics', [SmartDashboardController::class, 'getAnalytics'])->name('admin.smart.analytics');
    Route::get('smart/balances', [SmartDashboardController::class, 'getMemberBalances'])->name('admin.smart.balances');
    Route::get('smart/suggestions', [SmartDashboardController::class, 'getSuggestions'])->name('admin.smart.suggestions');
    Route::get('smart/notifications', [SmartDashboardController::class, 'getNotifications'])->name('admin.smart.notifications');
    Route::post('smart/clear-cache', [SmartDashboardController::class, 'clearCache'])->name('admin.smart.clearCache');

    //===============================MEMBER MANAGEMENT (Admin/Manager)====================================
    Route::get('member/addmembers', [AddmemberController::class, 'add'])->name('admin.add-member');
    Route::post('member/storemember', [AddmemberController::class, 'store'])->name('store.member');
    Route::get('member/viewmembers', [AddmemberController::class, 'view'])->name('admin.view-member');
    Route::get('member/deletedata/{id}', [AddmemberController::class, 'delete'])->name('admin.delete');
    Route::get('member/editpage', [AddmemberController::class, 'editView'])->name('admin.editpage');
    Route::get('member/editdata/{id}', [AddmemberController::class, 'edit'])->name('admin.edit-member');
    Route::post('member/updatemember', [AddmemberController::class, 'update'])->name('update.member');
    Route::get('member/memberStatus/{id}/{status}', [AddmemberController::class, 'memberStatus'])->name('memberStatus');
    Route::post('member/changeRole/{id}', [AddmemberController::class, 'changeRole'])->name('admin.member.changeRole');

    //===============================ROOM MANAGEMENT (Admin/Manager)====================================
    Route::get('room/initialize', [RoomController::class, 'initializeRooms'])->name('admin.room.initialize');
    Route::get('room', [RoomController::class, 'index'])->name('admin.room.index');
    Route::get('room/assign', [RoomController::class, 'assignForm'])->name('admin.room.assign');
    Route::post('room/assign', [RoomController::class, 'assignMember'])->name('admin.room.assign.store');
    Route::get('room/{id}', [RoomController::class, 'show'])->name('admin.room.show');
    Route::post('room/remove/{memberId}', [RoomController::class, 'removeMember'])->name('admin.room.remove');

    //===============================BILL MANAGEMENT (Admin/Manager)====================================
    Route::get('bill', [BillController::class, 'index'])->name('admin.bill.index');
    Route::get('bill/create', [BillController::class, 'create'])->name('admin.bill.create');
    Route::post('bill', [BillController::class, 'store'])->name('admin.bill.store');
    Route::get('bill/{id}/edit', [BillController::class, 'edit'])->name('admin.bill.edit');
    Route::post('bill/{id}', [BillController::class, 'update'])->name('admin.bill.update');
    Route::get('bill/{id}/view', [BillController::class, 'view'])->name('admin.bill.view');
    Route::get('bill/{id}/delete', [BillController::class, 'destroy'])->name('admin.bill.delete');

    // Bill Responsibility
    Route::get('bill-responsibility', [BillResponsibilityController::class, 'index'])->name('admin.billResponsibility.index');
    Route::post('bill-responsibility', [BillResponsibilityController::class, 'store'])->name('admin.billResponsibility.store');
    Route::put('bill-responsibility/{id}', [BillResponsibilityController::class, 'update'])->name('admin.billResponsibility.update');
    Route::get('bill-responsibility/{id}/delete', [BillResponsibilityController::class, 'destroy'])->name('admin.billResponsibility.destroy');

    //===============================SERVICE CHARGE (Admin/Manager)====================================
    Route::get('servicecharge', [ServiceChargeController::class, 'index'])->name('admin.servicecharge.index');
    Route::post('servicecharge', [ServiceChargeController::class, 'store'])->name('admin.servicecharge.store');
    Route::get('servicecharge/expenses', [ServiceChargeController::class, 'expenses'])->name('admin.servicecharge.expenses');
    Route::post('servicecharge/expense', [ServiceChargeController::class, 'storeExpense'])->name('admin.servicecharge.expense.store');
    Route::get('servicecharge/expense/{id}/delete', [ServiceChargeController::class, 'deleteExpense'])->name('admin.servicecharge.expense.delete');

    //===============================EXTRA PAYMENT (Admin/Manager)====================================
    Route::get('extra-payment', [MemberExtraPaymentController::class, 'index'])->name('admin.extra_payment.index');
    Route::get('extra-payment/create', [MemberExtraPaymentController::class, 'create'])->name('admin.extra_payment.create');
    Route::post('extra-payment', [MemberExtraPaymentController::class, 'store'])->name('admin.extra_payment.store');
    Route::get('extra-payment/{id}/edit', [MemberExtraPaymentController::class, 'edit'])->name('admin.extra_payment.edit');
    Route::post('extra-payment/{id}', [MemberExtraPaymentController::class, 'update'])->name('admin.extra_payment.update');
    Route::get('extra-payment/{id}/delete', [MemberExtraPaymentController::class, 'destroy'])->name('admin.extra_payment.delete');

    //===============================ROLE MANAGEMENT (Super Admin only)====================================
    Route::get('role', [RoleController::class, 'index'])->name('admin.role.index');
    Route::get('role/create', [RoleController::class, 'create'])->name('admin.role.create');
    Route::post('role/store', [RoleController::class, 'store'])->name('admin.role.store');
    Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('admin.role.edit');
    Route::post('role/update/{id}', [RoleController::class, 'update'])->name('admin.role.update');
    Route::get('role/{id}/delete', [RoleController::class, 'destroy'])->name('admin.role.delete');

    //===============================MENU MANAGEMENT (Super Admin only)====================================
    Route::get('menu', [MenuController::class, 'index'])->name('admin.menu.index');
    Route::get('menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('menu/store', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::post('menu/reorder', [MenuController::class, 'reorder'])->name('admin.menu.reorder');
    Route::get('menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::post('menu/update/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::get('menu/{id}/delete', [MenuController::class, 'destroy'])->name('admin.menu.delete');

    //===============================PROFILE (Unified — role-based inside controller)====================================
    Route::get('profile/view-profile', [AdminController::class, 'viewProfile'])->name('profile.view');
    Route::post('profile/updateProfile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/updatePassword', [AdminController::class, 'updatePassword'])->name('profile.change.password');
    Route::post('profile/updatePicture', [AdminController::class, 'updatePicture'])->name('profile.photo.change');
    // Legacy aliases so existing blade views still work
    Route::post('profile/updateProfile/admin', [AdminController::class, 'updateProfile'])->name('admin.update.profile');
    Route::post('profile/updatePassword/admin', [AdminController::class, 'updatePassword'])->name('admin.change.password');
    Route::post('profile/updatePicture/admin', [AdminController::class, 'updatePicture'])->name('admin.photo.change');
    Route::post('profile/updateProfile/user', [ProfileController::class, 'updateProfile'])->name('user.update.profile');
    Route::post('profile/updatePassword/user', [ProfileController::class, 'updatePassword'])->name('user.change.password');
    Route::post('profile/updatePicture/user', [ProfileController::class, 'updatePicture'])->name('user.photo.change');

    //===============================CATEGORY (Admin/Manager)====================================
    Route::get('category/addCategory', [FoodController::class, 'addCategory'])->name('admin.add.category');
    Route::get('category/viewCategory', [FoodController::class, 'viewCategory'])->name('admin.view.category');
    Route::get('category/deleteCategory/{id}', [FoodController::class, 'deleteCategory'])->name('admin.deleteCategory');
    Route::get('category/editCategory/{id}', [FoodController::class, 'editCategory'])->name('admin.editCategory');
    Route::post('category/storeCategory', [FoodController::class, 'storeCategory'])->name('store.category');
    Route::post('category/updateCategory', [FoodController::class, 'updateCategory'])->name('update.category');

    //===============================FOOD ITEM (Admin/Manager)====================================
    Route::get('food/addFoodItem', [FoodItemController::class, 'addFoodItem'])->name('admin.add.foodItem');
    Route::get('food/viewFoodItem', [FoodItemController::class, 'viewFoodItem'])->name('admin.view.foodItem');
    Route::get('food/deleteFoodItem/{id}', [FoodItemController::class, 'deleteFoodItem'])->name('admin.deleteFoodItem');
    Route::get('food/editFoodItem/{id}', [FoodItemController::class, 'editFoodItem'])->name('admin.editFoodItem');
    Route::post('food/storeFoodItem', [FoodItemController::class, 'storeFoodItem'])->name('store.foodItem');
    Route::post('food/updateFoodItem', [FoodItemController::class, 'updateFoodItem'])->name('update.foodItem');

    //===============================MEAL (Unified — role-based inside controller)====================================
    Route::get('meal/addMeal', [MealController::class, 'addMeal'])->name('admin.add.meal');
    Route::post('meal/storeMeal', [MealController::class, 'storeMeal'])->name('store.meal');
    Route::get('meal/viewMeal', [MealController::class, 'viewMeal'])->name('admin.view.meal');
    Route::get('meal/showMeal', [MealController::class, 'showMeal'])->name('admin.showMeal');
    Route::get('meal/deleteMeal/{id}', [MealController::class, 'deleteMeal'])->name('admin.deleteMeal');
    Route::get('meal/editMeal/{id}', [MealController::class, 'editMeal'])->name('admin.editMeal');
    Route::get('meal/mealDetails/{id}', [MealController::class, 'mealDetails'])->name('admin.mealDetails');
    Route::post('meal/updateMeal', [MealController::class, 'updateMeal'])->name('admin.update.meal');
    Route::get('meal/mealStatus/{id}/{status}', [MealController::class, 'mealStatus'])->name('mealStatus');
    Route::get('meal/downloadPdf', [MealController::class, 'downloadPdf'])->name('admin.meal.downloadPdf');
    Route::get('meal/individualPdf/{id}', [MealController::class, 'individualPdf'])->name('admin.everymeal.downloadPdf');
    // User meal sub-routes (pending, details)
    Route::get('meal/pendingMeal', [UserMealController::class, 'pendingMeal'])->name('user.pendingMeal');
    Route::get('meal/detailsMeal/{id}/{month}', [UserMealController::class, 'detailsMeal'])->name('user.detailsMeal');

    //===============================PAYMENT (Unified — role-based inside controller)====================================
    Route::get('payment/viewPayment', [PaymentController::class, 'viewPayment'])->name('admin.view.payment');
    Route::get('payment/addPayment', [PaymentController::class, 'addPayment'])->name('admin.add.payment');
    Route::post('payment/storePayment', [PaymentController::class, 'storePayment'])->name('store.payment');
    Route::get('payment/editPayment/{id}', [PaymentController::class, 'editPayment'])->name('admin.editPayment');
    Route::post('payment/updatePayment', [PaymentController::class, 'updatePayment'])->name('admin.updatePayment');
    Route::get('payment/deletePayment/{id}', [PaymentController::class, 'deletePayment'])->name('admin.deletePayment');
    Route::get('payment/downloadPaymentPdf', [PaymentController::class, 'downloadPdf'])->name('admin.payment.downloadPdf');
    Route::get('payment/myPayment', [PaymentController::class, 'myPayment'])->name('payment.myPayment');
    Route::get('payment/myPayment/downloadPdf', [PaymentController::class, 'myPaymentPdf'])->name('payment.myPayment.pdf');
    Route::get('payment/details/{member_id}/{month}', [PaymentController::class, 'getPaymentDetails'])->name('admin.payment.details');
    Route::get('payment/paymentStatus/{id}/{status}', [PaymentController::class, 'paymentStatus'])->name('paymentStatus');
    Route::get('user/payment/myPayment', function () { return redirect()->route('payment.myPayment'); })->name('user.payment.myPayment');
    Route::post('payment/getBillAmount', [PaymentController::class, 'getBillAmount'])->name('admin.payment.getBillAmount');
    // Legacy route name aliases for existing payment views
    Route::post('payment/storePayment/admin', [PaymentController::class, 'storePayment'])->name('admin.store.payment');
    Route::post('payment/updatePayment/admin', [PaymentController::class, 'updatePayment'])->name('admin.update.payment');
    Route::get('admin/payment/details/{member_id}/{month}', [PaymentController::class, 'getPaymentDetails']);
    Route::get('admin/payment/paymentStatus/{id}/{status}', [PaymentController::class, 'paymentStatus']);
    Route::get('admin/payment/editPayment/{id}', [PaymentController::class, 'editPayment']);
    // User payment sub-routes
    Route::post('payment/getBillAmount/user', [UserPaymentController::class, 'getBillAmount'])->name('user.payment.getBillAmount');
    Route::get('payment/pendingPayment', [UserPaymentController::class, 'pendingPayment'])->name('user.pendingPayment');
    Route::get('payment/details/{month}', [UserPaymentController::class, 'getPaymentDetails'])->name('user.payment.details');
    Route::get('user/payment/details/{month}', [UserPaymentController::class, 'getPaymentDetails']);

    //===============================EXPANSE (Unified — role-based inside controller)====================================
    Route::get('expanse/viewExpanse', [ExpanseController::class, 'viewExpanse'])->name('admin.view.expanse');
    Route::get('expanse/addExpanse', [ExpanseController::class, 'addExpanse'])->name('admin.add.expanse');
    Route::post('expanse/storeExpanse', [ExpanseController::class, 'storeExpanse'])->name('store.expanse');
    Route::get('expanse/deleteExpanse/{id}', [ExpanseController::class, 'deleteExpanse'])->name('admin.deleteExpanse');
    Route::get('expanse/deleteExpanseAlias/{id}', [ExpanseController::class, 'deleteExpanse'])->name('delete.expanse');
    Route::get('expanse/editExpanse/{id}', [ExpanseController::class, 'editExpanse'])->name('admin.editExpanse');
    Route::get('expanse/editExpanseAlias/{id}', [ExpanseController::class, 'editExpanse'])->name('edit.expanse');
    Route::post('expanse/updateExpanse', [ExpanseController::class, 'updateExpanse'])->name('admin.updateExpanse');
    Route::post('expanse/updateExpanseAlias', [ExpanseController::class, 'updateExpanse'])->name('update.expanse');
    Route::get('expanse/detailsExpanse/{invoice_no}', [ExpanseController::class, 'detailExpanse'])->name('details.expanse');
    Route::get('expanse/expanseStatus/{id}/{status}', [ExpanseController::class, 'expanseStatus'])->name('expanseStatus');
    Route::get('expanse/downloadPdf', [ExpanseController::class, 'downloadPdf'])->name('admin.expanse.downloadPdf');
    Route::get('expanse/downloadPdfAlias', [ExpanseController::class, 'downloadPdf'])->name('expanse.downloadPdf');
    Route::get('expanse/onChangeExpanse', [ExpanseController::class, 'onChange'])->name('expanse.onChange');
    // User expanse sub-routes
    Route::get('expanse/detailsExpanse/{invoice}/{id}', [UserExpanseController::class, 'detailsExpanse'])->name('user.details.expanse');
    Route::get('expanse/pendingExpanse', [UserExpanseController::class, 'pendingExpanse'])->name('user.pending.expanse');

    //===============================FOOD ADVANCE (Unified — role-based inside controller)====================================
    Route::get('foodAdvance', [FoodAdvanceController::class, 'index'])->name('admin.foodAdvance.index');
    Route::get('foodAdvance/create', [FoodAdvanceController::class, 'create'])->name('admin.foodAdvance.create');
    Route::post('foodAdvance/store', [FoodAdvanceController::class, 'store'])->name('admin.foodAdvance.store');
    Route::get('foodAdvance/edit/{id}', [FoodAdvanceController::class, 'edit'])->name('admin.foodAdvance.edit');
    Route::post('foodAdvance/update', [FoodAdvanceController::class, 'update'])->name('admin.foodAdvance.update');
    Route::get('foodAdvance/view/{id}', [FoodAdvanceController::class, 'view'])->name('admin.foodAdvance.view');
    Route::get('foodAdvance/delete/{id}', [FoodAdvanceController::class, 'delete'])->name('admin.foodAdvance.delete');

    //===============================TOTAL SUMMARY (Admin/Manager)====================================
    Route::get('totalSummary', [SummaryController::class, 'index'])->name('admin.total.summary');
    Route::get('memberDetails/{id}', [SummaryController::class, 'memberDetails'])->name('admin.memberDetails');

    //===============================REPORTS (Unified — role-based inside controller)====================================
    Route::get('totalReports', [ReportController::class, 'report'])->name('admin.total.report');
    Route::get('viewReports', [ReportController::class, 'view'])->name('admin.view.report');
    Route::get('report/downloadPdf/{one}/{two}', [ReportController::class, 'downloadPdf'])->name('admin.report.downloadPdf');
    // User report routes (backward compat and direct access)
    Route::get('report/viewReport', [ReportController::class, 'report'])->name('user.viewReport');
    Route::get('report/detailsReport', [ReportController::class, 'view'])->name('user.details.report');
    Route::get('report/userPdf/{one}/{two}', [ReportController::class, 'downloadPdf'])->name('user.report.downloadPdf');

    //===============================USER PROFILE (kept for backward compat, redirects to unified)====================================
    Route::get('user/profile/viewProfile', function () {
        return redirect()->route('profile.view');
    })->name('user.viewProfile');

    //===============================USER BILL (Redirect to unified /bill)====================================
    Route::get('mybill', function () { return redirect()->route('admin.bill.index'); })->name('user.bill.index');
    Route::get('mybill/view/{id}', function ($id) { return redirect()->route('admin.bill.view', $id); })->name('user.bill.view');

    //===============================USER MEAL (backward compat — same controller, role-based)====================================
    // These are now handled by the unified meal routes above
    // Legacy aliases for existing views that still use old route names
    Route::get('user/meal/viewMeal', function () { return redirect()->route('admin.view.meal'); })->name('user.viewMeal');
    Route::get('user/meal/addMeal', function () { return redirect()->route('admin.add.meal'); })->name('user.addMeal');
    Route::get('user/meal/pendingMeal', function () { return redirect()->route('user.pendingMeal'); });
    Route::post('user/meal/storeMeal', [UserMealController::class, 'storeMeal'])->name('store.user.meal');

    //===============================USER PAYMENT (backward compat)====================================
    Route::get('user/payment/viewPayment', function () { return redirect()->route('admin.view.payment'); })->name('user.viewPayment');
    Route::get('user/payment/addPayment', function () { return redirect()->route('admin.add.payment'); })->name('user.addPayment');
    Route::post('user/payment/storePayment', [UserPaymentController::class, 'storePayment'])->name('user.store.payment');

    //===============================USER EXPANSE (backward compat)====================================
    Route::get('user/expanse/viewExpanse', function () { return redirect()->route('admin.view.expanse'); })->name('user.viewExpanse');
    Route::get('user/expanse/addExpanse', function () { return redirect()->route('admin.add.expanse'); })->name('user.add.expanse');
    Route::post('user/expanse/storeExpanse', [UserExpanseController::class, 'storeExpanse'])->name('user.store.expanse');
    Route::post('user/expanse/updateExpanse', [UserExpanseController::class, 'updateExpanse'])->name('user.update.expanse');
    Route::get('user/expanse/editExpanse/{id}', [UserExpanseController::class, 'editExpanse'])->name('user.edit.expanse');

    //===============================USER FOOD ADVANCE (backward compat)====================================
    Route::get('user/foodAdvance', function () { return redirect()->route('admin.foodAdvance.index'); })->name('user.foodAdvance.index');
    Route::get('user/foodAdvance/create', function () { return redirect()->route('admin.foodAdvance.create'); })->name('user.foodAdvance.create');
    Route::post('user/foodAdvance/store', [UserFoodAdvanceController::class, 'store'])->name('user.foodAdvance.store');
    Route::get('user/foodAdvance/edit/{id}', function ($id) { return redirect()->route('admin.foodAdvance.edit', $id); })->name('user.foodAdvance.edit');
    Route::post('user/foodAdvance/update', [UserFoodAdvanceController::class, 'update'])->name('user.foodAdvance.update');
    Route::get('user/foodAdvance/view/{id}', function ($id) { return redirect()->route('admin.foodAdvance.view', $id); })->name('user.foodAdvance.view');

    //===============================USER REPORT (backward compat)====================================
    Route::get('user/report/viewReport', function () { return redirect()->route('user.viewReport'); });
    Route::get('user/report/detailsReport', function () { return redirect()->route('user.details.report'); });

    //===============================USER BILL (backward compat)====================================
    Route::get('user/bill', function () { return redirect()->route('user.bill.index'); });
    Route::get('user/bill/view/{id}', function ($id) { return redirect()->route('user.bill.view', $id); });

});

//===============================LEGACY REDIRECTS (old admin/* and user/* prefix URLs)====================================
Route::get('admin/dashboard', function () { return redirect()->route('dashboard'); })->name('admin.dashboard');
Route::get('user/dashboard', function () { return redirect()->route('dashboard'); })->name('user.dashboard');
Route::get('admin/profile/view-profile', function () { return redirect()->route('profile.view'); })->name('admin.view.profile');
Route::get('user/profile/viewProfile', function () { return redirect()->route('profile.view'); });

// Catch-all legacy redirects for old prefixed URLs
Route::get('admin/{any}', function ($any) {
    return redirect('/' . $any);
})->where('any', '.*');

Route::get('user/{any}', function ($any) {
    // Map some legacy user paths to new paths
    $map = [
        'meal/viewMeal'   => 'meal/viewMeal',
        'meal/addMeal'    => 'meal/addMeal',
        'payment/viewPayment' => 'payment/viewPayment',
        'payment/addPayment'  => 'payment/addPayment',
        'expanse/viewExpanse' => 'expanse/viewExpanse',
        'expanse/addExpanse'  => 'expanse/addExpanse',
        'report/viewReport'   => 'totalReports',
        'foodAdvance'         => 'foodAdvance',
        'bill'                => 'bill',
    ];
    return redirect('/' . ($map[$any] ?? $any));
})->where('any', '.*');
