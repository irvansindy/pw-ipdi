<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;   
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\EventManagementController;
use App\Http\Controllers\Admin\TransactionManagementController;
use App\Http\Controllers\UserContentController;

// Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');

// Route::get('/', [HomeController::class, 'index'])->name('index');
// Route::get('/home-user', [UserContentController::class, 'index'])->name('home-user');
Route::get('/', [UserContentController::class, 'index'])->name('home');
Route::get('/fetch-content', [UserContentController::class,'fetchContent'])->name('fetch-content');

Route::get('/jnns', [UserContentController::class,'viewJnns'])->name('jnns');
Route::get('/event-jnns', [UserContentController::class,'fetchEventJnns'])->name('event-jnns');

Route::get('/seminar', [UserContentController::class,'viewSeminar'])->name('seminar');
Route::get('/event-seminar', [UserContentController::class,'fetchEventSeminar'])->name('event-seminar');

Route::get('/resertifikasi', [UserContentController::class,'viewResertifikasi'])->name('resertifikasi');
Route::get('/event-resertifikasi', [UserContentController::class,'fetchEventResertifikasi'])->name('event-resertifikasi');

Route::get('/wkd', [UserContentController::class,'viewWKD'])->name('wkd');
Route::get('/event-wkd', [UserContentController::class,'fetchEventWKD'])->name('event-wkd');

Route::get('/events', [UserContentController::class,'viewEvents'])->name('events');
Route::get('/fetch-categories', [UserContentController::class,'fetchCategoriesEvent'])->name('fetch-categories');
Route::get('/fetch-events', [UserContentController::class,'fetchAllEvent'])->name('fetch-events');

Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Route::group(['middleware' => 'role:user'], function() {
// 	Route::get('/', [UserContentController::class, 'index'])->name('home');
// });

Route::group(['middleware' => ['auth', 'role:admin']], function() {
	Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

	Route::get('/users', [UserManagementController::class, 'index'])->name('users');
	Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles');
	Route::post('/role-store', [RoleManagementController::class, 'store'])->name('role-store');
	
	Route::get('/event', [EventManagementController::class, 'index'])->name('event');
	Route::get('/event-category', [EventManagementController::class, 'fetchCategory'])->name('event-category');
	Route::get('/event-fetch', [EventManagementController::class,'fetch'])->name('event-fetch');
	Route::post('/event-store', [EventManagementController::class, 'store'])->name('event-store');
	Route::get('/event-detail', [EventManagementController::class, 'detail'])->name('event-detail');
	Route::post('/event-update', [EventManagementController::class, 'update'])->name('event-update');
	
	Route::get('/transaction', [TransactionManagementController::class,'index'])->name('transaction');
	Route::get('/transaction-fetch', [TransactionManagementController::class,'fetch'])->name('transaction-fetch');
	Route::get('/transaction-detail', [TransactionManagementController::class,'detail'])->name('transaction-detail');
});


Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});