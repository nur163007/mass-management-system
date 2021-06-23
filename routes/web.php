<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AddmemberController;
use App\Http\Controllers\ExpanseController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SummaryController;
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

Route::get('/', function () {
    return view('admin.master');
});

// admin dashboard routing version and add members

Route::get('admin/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
Route::get('admin/addmembers',[AddmemberController::class,'add'])->name('admin.add-member');
Route::post('admin/storemember',[AddmemberController::class,'store'])->name('store.member');
Route::get('admin/viewmembers',[AddmemberController::class,'view'])->name('admin.view-member');
Route::get('admin/showmember',[AddmemberController::class,'showdata'])->name('admin.showdata');
Route::get('admin/deletedata/{id}',[AddmemberController::class,'delete'])->name('admin.delete');
Route::get('admin/editpage',[AddmemberController::class,'editView'])->name('admin.editpage');
Route::get('admin/editdata/{id}',[AddmemberController::class,'edit'])->name('admin.edit-member');
Route::post('admin/updatemember',[AddmemberController::class,'update'])->name('update.member');



// add category  route

Route::get('admin/addCategory',[FoodController::class,'addCategory'])->name('admin.add.category');
Route::post('admin/storeCategory',[FoodController::class,'storeCategory'])->name('store.category');
Route::get('admin/viewCategory',[FoodController::class,'viewCategory'])->name('admin.view.category');
Route::get('admin/showCategory',[FoodController::class,'showCategory'])->name('admin.showCategory');
Route::get('admin/deleteCategory/{id}',[FoodController::class,'deleteCategory'])->name('admin.deleteCategory');
Route::get('admin/editCategory/{id}',[FoodController::class,'editCategory'])->name('admin.editCategory');
Route::post('admin/updateCategory',[FoodController::class,'updateCategory'])->name('update.category');

// add food item routes

Route::get('admin/addFoodItem', [FoodItemController::class,'addFoodItem'])->name('admin.add.foodItem');
Route::post('admin/storeFoodItem',[FoodItemController::class,'storeFoodItem'])->name('store.foodItem');
Route::get('admin/viewFoodItem',[FoodItemController::class,'viewFoodItem'])->name('admin.view.foodItem');
Route::get('admin/showFoodItem',[FoodItemController::class,'showFoodItem'])->name('admin.showFoodItem');
Route::get('admin/deleteFoodItem/{id}',[FoodItemController::class,'deleteFoodItem'])->name('admin.deleteFoodItem');
Route::get('admin/editFoodItem/{id}',[FoodItemController::class,'editFoodItem'])->name('admin.editFoodItem');
Route::post('admin/updateFoodItem',[FoodItemController::class,'updateFoodItem'])->name('update.foodItem');

// add meal routes
Route::get('admin/addMeal',[MealController::class,'addMeal'])->name('admin.add.meal');
Route::post('admin/storeMeal',[MealController::class,'storeMeal'])->name('store.meal');
Route::get('admin/viewMeal',[MealController::class,'viewMeal'])->name('admin.view.meal');
Route::get('admin/showMeal',[MealController::class,'showMeal'])->name('admin.showMeal');
Route::get('admin/deleteMeal/{id}',[MealController::class,'deleteMeal'])->name('admin.deleteMeal');
Route::get('admin/editMeal/{id}',[MealController::class,'editMeal'])->name('admin.editMeal');
Route::get('admin/mealDetails/{id}',[MealController::class,'mealDetails'])->name('admin.mealDetails');
Route::post('admin/updateMeal',[MealController::class,'updateMeal'])->name('admin.update.meal');

// add expanse route
Route::get('admin/addExpanse',[ExpanseController::class,'addExpanse'])->name('admin.add.expanse');
Route::get('admin/viewExpanse',[ExpanseController::class,'viewExpanse'])->name('admin.view.expanse');
Route::post('admin/storeExpanse',[ExpanseController::class,'storeExpanse'])->name('store.expanse');
Route::get('admin/onChangeExpanse',[ExpanseController::class,'onChange'])->name('expanse.onChange');
// Route::get('admin/showExpanse',[ExpanseController::class,'showExpanse'])->name('admin.showExpanse');
Route::get('admin/deleteExpanse/{id}',[ExpanseController::class,'deleteExpanse'])->name('delete.expanse');
// Route::get('admin/deleteExpanseDetails/{id}',[ExpanseController::class,'deleteDetails'])->name('delete.expanseDetails');
Route::get('admin/editExpanse/{id}',[ExpanseController::class,'editExpanse'])->name('edit.expanse');
Route::post('admin/updateExpanse',[ExpanseController::class,'updateExpanse'])->name('update.expanse');
Route::get('admin/detailsExpanse/{invoice_no}',[ExpanseController::class,'detailExpanse'])->name('details.expanse');


// payment method routes
Route::get('admin/addPayment',[PaymentController::class,'addPayment'])->name('admin.add.payment');
Route::get('admin/viewPayment',[PaymentController::class,'viewPayment'])->name('admin.view.payment');
Route::post('admin/storePayment',[PaymentController::class,'storePayment'])->name('admin.store.payment');
Route::get('admin/deletePayment/{id}',[PaymentController::class,'deletePayment'])->name('admin.delete.payment');
Route::get('admin/editPayment/{id}',[PaymentController::class,'editPayment'])->name('admin.edit.payment');
Route::post('admin/updatePayment',[PaymentController::class,'updatePayment'])->name('admin.update.payment');


// total summary route
Route::get('admin/totalSummary',[SummaryController::class,'index'])->name('admin.total.summary');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
