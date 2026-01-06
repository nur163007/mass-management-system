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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




//===============================LOGIN ROUTE START====================================
Route::get('/', [LoginController::class,'showLoginForm']);
Route::post('loginCheck', [LoginController::class,'loginCheck'])->name('loginCheck');
Route::get('user_logout', [LoginController::class,'userLogout'])->name('user_logout');

// Shared routes accessible by both admin and user
Route::post('admin/category/storeCategory',[FoodController::class,'storeCategory'])->name('store.category');
Route::post('admin/food/storeFoodItem',[FoodItemController::class,'storeFoodItem'])->name('store.foodItem');
Route::get('admin/expanse/onChangeExpanse',[ExpanseController::class,'onChange'])->name('expanse.onChange');

Route::middleware('admin')->group(function(){

//===============================LOGIN ROUTE END====================================

//===============================ADMIN ROUTE START====================================
Route::get('admin/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');

//===============================SMART DASHBOARD ROUTES START====================================
Route::get('admin/smart/analytics',[SmartDashboardController::class,'getAnalytics'])->name('admin.smart.analytics');
Route::get('admin/smart/balances',[SmartDashboardController::class,'getMemberBalances'])->name('admin.smart.balances');
Route::get('admin/smart/suggestions',[SmartDashboardController::class,'getSuggestions'])->name('admin.smart.suggestions');
Route::get('admin/smart/notifications',[SmartDashboardController::class,'getNotifications'])->name('admin.smart.notifications');
Route::post('admin/smart/clear-cache',[SmartDashboardController::class,'clearCache'])->name('admin.smart.clearCache');
//===============================SMART DASHBOARD ROUTES END====================================
Route::get('admin/member/addmembers',[AddmemberController::class,'add'])->name('admin.add-member');
Route::post('admin/member/storemember',[AddmemberController::class,'store'])->name('store.member');
Route::get('admin/member/viewmembers',[AddmemberController::class,'view'])->name('admin.view-member');
// Route::get('admin/showmember',[AddmemberController::class,'showdata'])->name('admin.showdata');
Route::get('admin/member/deletedata/{id}',[AddmemberController::class,'delete'])->name('admin.delete');
Route::get('admin/member/editpage',[AddmemberController::class,'editView'])->name('admin.editpage');
Route::get('admin/member/editdata/{id}',[AddmemberController::class,'edit'])->name('admin.edit-member');
Route::post('admin/member/updatemember',[AddmemberController::class,'update'])->name('update.member');
Route::get('admin/member/memberStatus/{id}/{status}',[AddmemberController::class,'memberStatus'])->name('memberStatus');
Route::post('admin/member/changeRole/{id}',[AddmemberController::class,'changeRole'])->name('admin.member.changeRole');
//===============================ADMIN ROUTE END==============================================

//===============================ROOM MANAGEMENT ROUTES START====================================
Route::get('admin/room/initialize',[RoomController::class,'initializeRooms'])->name('admin.room.initialize');
Route::get('admin/room',[RoomController::class,'index'])->name('admin.room.index');
Route::get('admin/room/assign',[RoomController::class,'assignForm'])->name('admin.room.assign');
Route::post('admin/room/assign',[RoomController::class,'assignMember'])->name('admin.room.assign.store');
Route::get('admin/room/{id}',[RoomController::class,'show'])->name('admin.room.show');
Route::post('admin/room/remove/{memberId}',[RoomController::class,'removeMember'])->name('admin.room.remove');
//===============================ROOM MANAGEMENT ROUTES END====================================

//===============================BILL MANAGEMENT ROUTES START====================================
Route::get('admin/bill',[BillController::class,'index'])->name('admin.bill.index');
Route::get('admin/bill/create',[BillController::class,'create'])->name('admin.bill.create');
Route::post('admin/bill',[BillController::class,'store'])->name('admin.bill.store');
Route::get('admin/bill/{id}/edit',[BillController::class,'edit'])->name('admin.bill.edit');
Route::post('admin/bill/{id}',[BillController::class,'update'])->name('admin.bill.update');
Route::get('admin/bill/{id}/delete',[BillController::class,'destroy'])->name('admin.bill.delete');
//===============================BILL MANAGEMENT ROUTES END====================================

//===============================SERVICE CHARGE ROUTES START====================================
Route::get('admin/servicecharge',[ServiceChargeController::class,'index'])->name('admin.servicecharge.index');
Route::post('admin/servicecharge',[ServiceChargeController::class,'store'])->name('admin.servicecharge.store');
Route::get('admin/servicecharge/expenses',[ServiceChargeController::class,'expenses'])->name('admin.servicecharge.expenses');
Route::post('admin/servicecharge/expense',[ServiceChargeController::class,'storeExpense'])->name('admin.servicecharge.expense.store');
Route::get('admin/servicecharge/expense/{id}/delete',[ServiceChargeController::class,'deleteExpense'])->name('admin.servicecharge.expense.delete');
//===============================SERVICE CHARGE ROUTES END====================================

//===============================EXTRA PAYMENT ROUTES START====================================
Route::get('admin/extra-payment',[MemberExtraPaymentController::class,'index'])->name('admin.extra_payment.index');
Route::get('admin/extra-payment/create',[MemberExtraPaymentController::class,'create'])->name('admin.extra_payment.create');
Route::post('admin/extra-payment',[MemberExtraPaymentController::class,'store'])->name('admin.extra_payment.store');
Route::get('admin/extra-payment/{id}/edit',[MemberExtraPaymentController::class,'edit'])->name('admin.extra_payment.edit');
Route::post('admin/extra-payment/{id}',[MemberExtraPaymentController::class,'update'])->name('admin.extra_payment.update');
Route::get('admin/extra-payment/{id}/delete',[MemberExtraPaymentController::class,'destroy'])->name('admin.extra_payment.delete');
//===============================EXTRA PAYMENT ROUTES END====================================


//===============================ADMIN PROFILE VIEW ROUTE END==============================================
Route::get('admin/profile/view-profile',[AdminController::class,'viewProfile'])->name('admin.view.profile');
Route::post('admin/profile/updateProfile',[AdminController::class,'updateProfile'])->name('admin.update.profile');
Route::post('admin/profile/updatePassword',[AdminController::class,'updatePassword'])->name('admin.change.password');
Route::post('admin/profile/updatePicture',[AdminController::class,'updatePicture'])->name('admin.photo.change');


//===============================CATEGORY ROUTE START=======================================================
Route::get('admin/category/addCategory',[FoodController::class,'addCategory'])->name('admin.add.category');
Route::get('admin/category/viewCategory',[FoodController::class,'viewCategory'])->name('admin.view.category');
// Route::get('admin/showCategory',[FoodController::class,'showCategory'])->name('admin.showCategory');
Route::get('admin/category/deleteCategory/{id}',[FoodController::class,'deleteCategory'])->name('admin.deleteCategory');
Route::get('admin/category/editCategory/{id}',[FoodController::class,'editCategory'])->name('admin.editCategory');
Route::post('admin/category/updateCategory',[FoodController::class,'updateCategory'])->name('update.category');
//===============================CATEGORY ROUTE END=========================================================


//===============================FOOD ITEM ROUTE START==================================================
Route::get('admin/food/addFoodItem', [FoodItemController::class,'addFoodItem'])->name('admin.add.foodItem');
Route::get('admin/food/viewFoodItem',[FoodItemController::class,'viewFoodItem'])->name('admin.view.foodItem');
// Route::get('admin/showFoodItem',[FoodItemController::class,'showFoodItem'])->name('admin.showFoodItem');
Route::get('admin/food/deleteFoodItem/{id}',[FoodItemController::class,'deleteFoodItem'])->name('admin.deleteFoodItem');
Route::get('admin/food/editFoodItem/{id}',[FoodItemController::class,'editFoodItem'])->name('admin.editFoodItem');
Route::post('admin/food/updateFoodItem',[FoodItemController::class,'updateFoodItem'])->name('update.foodItem');
//===============================FOOD ITEM ROUTE END=======================================================


//==============================MEAL ROUTE START===================================================
Route::get('admin/meal/addMeal',[MealController::class,'addMeal'])->name('admin.add.meal');
Route::post('admin/meal/storeMeal',[MealController::class,'storeMeal'])->name('store.meal');
Route::get('admin/meal/viewMeal',[MealController::class,'viewMeal'])->name('admin.view.meal');
Route::get('admin/meal/showMeal',[MealController::class,'showMeal'])->name('admin.showMeal');
Route::get('admin/meal/deleteMeal/{id}',[MealController::class,'deleteMeal'])->name('admin.deleteMeal');
Route::get('admin/meal/editMeal/{id}',[MealController::class,'editMeal'])->name('admin.editMeal');
Route::get('admin/meal/mealDetails/{id}',[MealController::class,'mealDetails'])->name('admin.mealDetails');
Route::post('admin/meal/updateMeal',[MealController::class,'updateMeal'])->name('admin.update.meal');
Route::get('admin/meal/mealStatus/{id}/{status}',[MealController::class,'mealStatus'])->name('mealStatus');
Route::get('admin/meal/downloadPdf',[MealController::class,'downloadPdf'])->name('admin.meal.downloadPdf');
Route::get('admin/meal/individual/downloadPdf/{id}',[MealController::class,'individualPdf'])->name('admin.everymeal.downloadPdf');

//===============================MEAL ROUTE END===================================================


//=======================================EXPANSES ROUTE START=============================================
Route::get('admin/expanse/addExpanse',[ExpanseController::class,'addExpanse'])->name('admin.add.expanse');
Route::get('admin/expanse/viewExpanse',[ExpanseController::class,'viewExpanse'])->name('admin.view.expanse');
Route::post('admin/expanse/storeExpanse',[ExpanseController::class,'storeExpanse'])->name('store.expanse');
// Route::get('admin/showExpanse',[ExpanseController::class,'showExpanse'])->name('admin.showExpanse');
Route::get('admin/expanse/deleteExpanse/{id}',[ExpanseController::class,'deleteExpanse'])->name('delete.expanse');
// Route::get('admin/deleteExpanseDetails/{id}',[ExpanseController::class,'deleteDetails'])->name('delete.expanseDetails');
Route::get('admin/expanse/editExpanse/{id}',[ExpanseController::class,'editExpanse'])->name('edit.expanse');
Route::post('admin/expanse/updateExpanse',[ExpanseController::class,'updateExpanse'])->name('update.expanse');
Route::get('admin/expanse/detailsExpanse/{invoice_no}',[ExpanseController::class,'detailExpanse'])->name('details.expanse');
Route::get('admin/expanse/expanseStatus/{id}/{status}',[ExpanseController::class,'expanseStatus'])->name('expanseStatus');
Route::get('admin/expanse/downloadPdf',[ExpanseController::class,'downloadPdf'])->name('admin.expanse.downloadPdf');
//========================================EXPANSES ROUTE END=================================================

//=======================================FOOD ADVANCE ROUTE START=============================================
Route::get('admin/foodAdvance',[FoodAdvanceController::class,'index'])->name('admin.foodAdvance.index');
Route::get('admin/foodAdvance/create',[FoodAdvanceController::class,'create'])->name('admin.foodAdvance.create');
Route::post('admin/foodAdvance/store',[FoodAdvanceController::class,'store'])->name('admin.foodAdvance.store');
Route::get('admin/foodAdvance/approve/{id}',[FoodAdvanceController::class,'approve'])->name('admin.foodAdvance.approve');
Route::get('admin/foodAdvance/edit/{id}',[FoodAdvanceController::class,'edit'])->name('admin.foodAdvance.edit');
Route::post('admin/foodAdvance/update',[FoodAdvanceController::class,'update'])->name('admin.foodAdvance.update');
Route::get('admin/foodAdvance/view/{id}',[FoodAdvanceController::class,'view'])->name('admin.foodAdvance.view');
//========================================FOOD ADVANCE ROUTE END=================================================


//=======================================PAYMENT ROUTE START=============================================
Route::get('admin/payment/addPayment',[PaymentController::class,'addPayment'])->name('admin.add.payment');
Route::get('admin/payment/viewPayment',[PaymentController::class,'viewPayment'])->name('admin.view.payment');
Route::post('admin/payment/storePayment',[PaymentController::class,'storePayment'])->name('admin.store.payment');
Route::post('admin/payment/getBillAmount',[PaymentController::class,'getBillAmount'])->name('admin.payment.getBillAmount');
Route::get('admin/payment/deletePayment/{id}',[PaymentController::class,'deletePayment'])->name('admin.delete.payment');
Route::get('admin/payment/editPayment/{id}',[PaymentController::class,'editPayment'])->name('admin.edit.payment');
Route::post('admin/payment/updatePayment',[PaymentController::class,'updatePayment'])->name('admin.update.payment');
Route::get('admin/payment/paymentStatus/{id}/{status}',[PaymentController::class,'paymentStatus'])->name('paymentStatus');
Route::get('admin/payment/viewDetails/{id}',[PaymentController::class,'viewPaymentDetails'])->name('admin.view.payment.details');
Route::get('admin/payment/downloadPdf',[PaymentController::class,'downloadPdf'])->name('admin.payment.downloadPdf');


//=======================================PAYMENT ROUTE END====================================================


//=======================================SUMMARY ROUTE START=============================================
Route::get('admin/totalSummary',[SummaryController::class,'index'])->name('admin.total.summary');
Route::get('admin/memberDetails/{id}',[SummaryController::class,'memberDetails'])->name('admin.memberDetails');
//=======================================SUMMARY ROUTE END=============================================


//=======================================REPORT ROUTE START============================================
Route::get('admin/totalReports',[ReportController::class,'report'])->name('admin.total.report');
Route::get('admin/viewReports',[ReportController::class,'view'])->name('admin.view.report');
Route::get('admin/report/downloadPdf/{one}/{two}',[ReportController::class,'downloadPdf'])->name('admin.report.downloadPdf');

//=======================================REPORT ROUTE END============================================
//=======================================ADMIN ROUTE END============================================

});

//=======================================USER REGISTRATION ROUTE START============================================
Route::get('user_registration', [UserController::class,'userRegistration'])->name('user.register');
Route::post('member/register',[UserController::class,'register'])->name('register.user');
//=======================================USER REGISTRATION ROUTE END============================================


Route::middleware('user')->group(function(){


//=======================================USER ROUTE START============================================
Route::get('user/dashboard',[UserController::class,'dashboard'])->name('user.dashboard');

//======================================= USER PROFILE ROUTE START============================================
Route::get('user/profile/viewProfile',[ProfileController::class,'index'])->name('user.viewProfile');
Route::post('user/profile/updateProfile',[ProfileController::class,'updateProfile'])->name('user.update.profile');
Route::post('user/profile/updatePassword',[ProfileController::class,'updatePassword'])->name('user.change.password');
Route::post('user/profile/updatePicture',[ProfileController::class,'updatePicture'])->name('user.photo.change');


//======================================= USER MEAL ROUTE START============================================
Route::get('user/meal/viewMeal',[UserMealController::class,'index'])->name('user.viewMeal');
Route::get('user/meal/addMeal',[UserMealController::class,'addMeal'])->name('user.addMeal');
Route::post('user/meal/storeMeal',[UserMealController::class,'storeMeal'])->name('store.user.meal');
Route::get('user/meal/pendingMeal',[UserMealController::class,'pendingMeal'])->name('user.pendingMeal');
Route::get('user/meal/detailsMeal/{id}/{month}',[UserMealController::class,'detailsMeal'])->name('user.detailsMeal');


//======================================= USER PAYMENT ROUTE START============================================
Route::get('user/payment/viewPayment',[UserPaymentController::class,'index'])->name('user.viewPayment');
Route::get('user/payment/addPayment',[UserPaymentController::class,'addPayment'])->name('user.addPayment');
Route::post('user/payment/storePayment',[UserPaymentController::class,'storePayment'])->name('user.store.payment');
Route::get('user/payment/pendingPayment',[UserPaymentController::class,'pendingPayment'])->name('user.pendingPayment');
Route::get('user/payment/details/{month}',[UserPaymentController::class,'getPaymentDetails'])->name('user.payment.details');
// ====================================USER PAYMENT ROUTE END=======================================


//======================================= USER EXPANSE ROUTE START============================================
Route::get('user/expanse/viewExpanse',[UserExpanseController::class,'index'])->name('user.viewExpanse');
Route::get('user/expanse/detailsExpanse/{invoice}/{id}',[UserExpanseController::class,'detailsExpanse'])->name('user.details.expanse');
Route::get('user/expanse/addExpanse',[UserExpanseController::class,'addExpanse'])->name('user.add.expanse');
Route::post('user/expanse/storeExpanse',[UserExpanseController::class,'storeExpanse'])->name('user.store.expanse');
Route::get('user/expanse/pendingExpanse',[UserExpanseController::class,'pendingExpanse'])->name('user.pending.expanse');
Route::get('user/expanse/editExpanse/{id}',[UserExpanseController::class,'editExpanse'])->name('user.edit.expanse');
Route::post('user/expanse/updateExpanse',[UserExpanseController::class,'updateExpanse'])->name('user.update.expanse');

//=======================================USER FOOD ADVANCE ROUTE START=============================================
Route::get('user/foodAdvance',[UserFoodAdvanceController::class,'index'])->name('user.foodAdvance.index');
Route::get('user/foodAdvance/create',[UserFoodAdvanceController::class,'create'])->name('user.foodAdvance.create');
Route::post('user/foodAdvance/store',[UserFoodAdvanceController::class,'store'])->name('user.foodAdvance.store');
Route::get('user/foodAdvance/edit/{id}',[UserFoodAdvanceController::class,'edit'])->name('user.foodAdvance.edit');
Route::post('user/foodAdvance/update',[UserFoodAdvanceController::class,'update'])->name('user.foodAdvance.update');
Route::get('user/foodAdvance/view/{id}',[UserFoodAdvanceController::class,'view'])->name('user.foodAdvance.view');
//========================================USER FOOD ADVANCE ROUTE END=================================================
// =============================USER EXPANSE ROUTE END=============================================

//======================================= USER REPORT ROUTE START============================================
Route::get('user/report/viewReport',[UserReportController::class,'index'])->name('user.viewReport');
Route::get('user/report/detailsReport',[UserReportController::class,'detailsReport'])->name('user.details.report');
Route::get('user/report/downloadPdf/{one}/{two}',[UserReportController::class,'downloadPdf'])->name('user.report.downloadPdf');

});

// ============================USER ROUTE END===============================
