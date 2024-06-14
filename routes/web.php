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
use App\Http\Controllers\Admin\VisionAndMisionController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\OrganizationStructure;
use App\Http\Controllers\Admin\IuranController;
use App\Http\Controllers\UserContentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VisionAndMisionController as userVisiMision;
use App\Http\Controllers\HistoryController as userHistory;
use App\Http\Controllers\IuranController as userIuran;

// Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');

// Route::get('/', [HomeController::class, 'index'])->name('index');
// Route::get('/home-user', [UserContentController::class, 'index'])->name('home-user');
Route::get('/', [UserContentController::class, 'index'])->name('home');
Route::get('/fetch-content', [UserContentController::class,'fetchContent'])->name('fetch-content');

Route::get('/events', [UserContentController::class,'viewEvents'])->name('events');
Route::get('/fetch-categories', [UserContentController::class,'fetchCategoriesEvent'])->name('fetch-categories');
Route::get('/fetch-events', [UserContentController::class,'fetchAllEvent'])->name('fetch-events');
Route::get('/visi-misi', [UserContentController::class,'visionAndMision'])->name('visi-misi');

Route::get('/visi-dan-misi', [userVisiMision::class, 'index'])->name('visi-dan-misi');
Route::get('/fetch-visi-misi', [userVisiMision::class, 'visionAndMision'])->name('fetch-visi-misi');

Route::get('/sejarah', [userHistory::class, 'index'])->name('sejarah');
Route::get('/fetch-sejarah', [userHistory::class, 'fetchHistory'])->name('fetch-sejarah');

Route::get('struktur-organisasi', [UserContentController::class,'organizationalStructure'])->name('struktur-organisasi');

Route::get('/event-registration', [UserContentController::class,'eventRegistration'])->name('event-registration');


Route::group(['middleware' => ['auth', 'role:user']], function() {
	Route::post('/check-transaction', [TransactionController::class,'checkTransaction'])->name('check-transaction');
	Route::post('/create-transaction', [TransactionController::class,'createTransaction'])->name('create-transaction');
	Route::get('/list-transaksi', [TransactionController::class,'viewTransaction'])->name('list-transaksi');
	Route::get('/histori-transaksi', [TransactionController::class,'transactionHistory'])->name('histori-transaksi');
	Route::get('/detail-transaksi', [TransactionController::class,'detailTransaction'])->name('detail-transaksi');
	Route::post('/upload-transaction', [TransactionController::class,'uploadTransaction'])->name('upload-transaction');

	Route::get('/iuran-anggota', [userIuran::class,'index'])->name('iuran-anggota');
	Route::get('/fetch-iuran-anggota', [userIuran::class,'fetchIuran'])->name('fetch-iuran-anggota');
	Route::get('/fetch-transaksi-iuran', [userIuran::class,'fetchTransaksiIuran'])->name('fetch-transaksi-iuran');
	Route::get('/detail-transaksi-iuran', [userIuran::class,'detailTransaksiIuran'])->name('detail-transaksi-iuran');
	Route::get('/fetch-peserta', [userIuran::class,'peserta'])->name('fetch-peserta');
	Route::post('/store-iuran-peserta', [userIuran::class,'storeIuran'])->name('store-iuran-peserta');
	Route::post('/upload-iuran-peserta', [userIuran::class,'uploadIuran'])->name('upload-iuran-peserta');
});

Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => ['auth', 'role:admin']], function() {
	Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

	Route::get('/users', [UserManagementController::class, 'index'])->name('users');
	Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles');
	Route::post('/role-store', [RoleManagementController::class, 'store'])->name('role-store');
	
	Route::get('/event', [EventManagementController::class, 'index'])->name('event');
	Route::get('/event-category', [EventManagementController::class, 'fetchCategory'])->name('event-category');
	Route::get('/event-hotel', [EventManagementController::class, 'fetchHotel'])->name('event-hotel');
	Route::get('/event-fetch', [EventManagementController::class,'fetch'])->name('event-fetch');
	Route::post('/event-store', [EventManagementController::class, 'store'])->name('event-store');
	Route::get('/event-detail', [EventManagementController::class, 'detail'])->name('event-detail');
	Route::post('/event-update', [EventManagementController::class, 'update'])->name('event-update');

	Route::get('/iuran', [IuranController::class,'index'])->name('iuran');
	Route::get('/fetch-iuran', [IuranController::class,'fetchIuran'])->name('fetch-iuran');
	Route::get('/detail-iuran', [IuranController::class,'detailIuran'])->name('detail-iuran');
	Route::post('/store-iuran', [IuranController::class,'storeIuran'])->name('store-iuran');
	Route::post('/update-iuran', [IuranController::class,'updateIuran'])->name('update-iuran');
	
	Route::get('/transaction', [TransactionManagementController::class,'index'])->name('transaction');
	Route::get('/transaction-fetch', [TransactionManagementController::class,'fetch'])->name('transaction-fetch');
	Route::get('/transaction-detail', [TransactionManagementController::class,'detail'])->name('transaction-detail');
	Route::post('/transaction-approval', [TransactionManagementController::class,'approval'])->name('transaction-approval');
	// Route::get('/transaction-approval', [TransactionManagementController::class,'approval'])->name('transaction-approval');

	Route::get('/vision-mision', [VisionAndMisionController::class,'viewVisionMision'])->name('vision-mision');
	Route::get('/fetch-vision', [VisionAndMisionController::class,'fetchVision'])->name('fetch-vision');
	Route::get('/fetch-mision', [VisionAndMisionController::class,'fetchMision'])->name('fetch-mision');
	Route::get('/detail-vision', [VisionAndMisionController::class,'detailVision'])->name('detail-vision');
	Route::post('/upsert-vision', [VisionAndMisionController::class,'createOrUpdateVision'])->name('upsert-vision');
	Route::get('/detail-mision', [VisionAndMisionController::class,'detailMision'])->name('detail-mision');
	Route::post('/upsert-mision', [VisionAndMisionController::class,'createOrUpdateMision'])->name('upsert-mision');
	
	Route::get('/history', [HistoryController::class,'index'])->name('history');
	Route::get('/fetch-history', [HistoryController::class,'fetchHistory'])->name('fetch-history');
	Route::get('/detail-history', [HistoryController::class,'detailHistory'])->name('detail-history');
	Route::post('/upsert-history', [HistoryController::class,'createOrUpdateHistory'])->name('upsert-history');
	
	Route::get('/organization', [OrganizationStructure::class,'index'])->name('organization');
	Route::get('/fetch-organization', [OrganizationStructure::class,'fetchOrganization'])->name('fetch-organization');
	Route::post('/upload-video', [OrganizationStructure::class,'upload'])->name('upload-video');
});

Route::get('/send-mail', [TransactionManagementController::class,'sendEmail'])->name('send-mail');
Route::get('/generate-pdf', [TransactionManagementController::class,'generatePDF'])->name('generate-pdf');
Route::get('/generate-qrcode', [TransactionManagementController::class,'generateQrCode'])->name('generate-qrcode');

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