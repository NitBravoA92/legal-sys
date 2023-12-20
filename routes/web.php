<?php

use App\Models\Product;

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderNotificationsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ClientNotificationsController;
use App\Http\Controllers\ManagementNotificationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppNotificationController;

use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

//for Customer users
Route::prefix('client-area')->group(function() {

	Route::group(['middleware' => 'auth', 'middleware' => 'verified'], function () {

		//dashboard for client
		Route::get('/summery-client', [DashboardController::class, 'client'])->middleware('translate')
		->name('summery-client');

		//update customers profile -> routes
		Route::get('/user-profile', [InfoUserController::class, 'create'])->middleware('translate');
		Route::post('/client-profile', [InfoUserController::class, 'store']);

		//Orders
		Route::get('/service-orders/create-service-order/{id}', [OrderController::class, 'create'])->middleware('translate');
		Route::post('/service-orders/create-service-order/{id}', [OrderController::class, 'store']);
		Route::get('/service-orders/show-service-order/{id}', [OrderController::class, 'show'])->middleware('translate');
		Route::get('/service-orders/cancel-service-order/{id}', [OrderController::class, 'cancel'])->middleware('translate');

		//Orders Notifications
		Route::post('/send-order-notification/{id}', [OrderNotificationsController::class, 'store']);

		//show orders list
		Route::resource('service-orders', OrderController::class)->middleware('translate');

		//Documents Routes
		Route::get('documents/download-files/{id}', [DocumentController::class, 'download'])->name('documents.download-client');
		Route::resource('documents', DocumentController::class)->middleware('translate');

		//notifications
		Route::get('/notifications', [AppNotificationController::class, 'client'])->middleware('auth')->middleware('translate')->name('notifications.client');
		Route::get('/notifications/update', [AppNotificationController::class, 'client_update'])->middleware('translate')->name('notifications.client-update');

		//Sessions routes
		Route::get('/login', function () {
			return redirect()->route('summery-client');
		})->middleware('translate')->name('sign-in');

	});

	//routes for login, register and verify email
	Route::group(['middleware' => 'guest'], function () {

		Route::get('/register', [RegisterController::class, 'create']);
		Route::post('/register', [RegisterController::class, 'store']);
		Route::get('/login', [SessionsController::class, 'create_client'])->name('login.client');
		Route::post('/session', [SessionsController::class, 'store_clients']);

		//verify client account before login
		Route::get('/email/verify', [RegisterController::class, 'verify'])->name('verification.notice');
		Route::post('/email/verification-notification', [RegisterController::class, 'send_link'])->middleware('throttle:6,1')->name('verification.send');
		Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'user_verified'])->name('verification.verify');

		Route::get('/login/forgot-password', [ResetController::class, 'createClient']);
		Route::post('/forgot-password', [ResetController::class, 'sendEmailClient']);

		Route::get('/reset-password/{token}', [ResetController::class, 'resetPassClient'])->name('password.reset_client');
		Route::post('/reset-password', [ChangePasswordController::class, 'changePasswordClient'])->name('password.update');

	});

});


//routes for Users: SUPER ADMINISTRATOR, ADMINISTRATOR, VALIDATOR, CALL CENTER
Route::prefix('management-area')->group(function(){

	Route::group(['middleware' => 'auth'], function () {

		//Dashboard for super administrator user
		Route::get('/summery-admin', [DashboardController::class, 'super_admin'])->middleware('translate')
		->name('summery-admin');

		//Dashboard for administrator user
		Route::get('/summery-accounter', [DashboardController::class, 'accounter'])->middleware('translate')
		->name('summery-accounter');

		//Users Routes -> only for SUPER ADMINISTRATOR
		Route::post('/users/status/block/{id}', [UserController::class, 'block'])->name('users.block');
		Route::post('/users/status/active/{id}', [UserController::class, 'active'])->name('users.active');
		Route::resource('users', UserController::class)->middleware('translate');

		//client routes: allows list, add, edit and delete clients | for SUPER ADMIN & ADMIN USERS
		Route::resource('clients', ClientController::class)->middleware('translate');

		//update user profile routes ->for all users
		Route::get('/user-profile', [InfoUserController::class, 'create'])->middleware('translate');
		Route::post('/user-profile', [InfoUserController::class, 'store'])->middleware('translate');

		//route for validator users -> show list of assigned orders
		Route::get('/assigned-service-orders', [OrderController::class, 'assigned'])->middleware('translate');

		//routes for update orders status
		Route::get('/clients-service-orders/validate-order/{id}', [OrderController::class, 'validated'])->middleware('translate');
		Route::get('/clients-service-orders/take-order/{id}', [OrderController::class, 'in_process'])->middleware('translate');
		Route::get('/clients-service-orders/request-documents/{id}', [OrderController::class, 'request_documents'])->middleware('translate');
		Route::post('/clients-service-orders/request-documents/{id}', [OrderController::class, 'send_request_additional_documents'])->name('clients-service-orders.request-documents');
		Route::post('/clients-service-orders/order-completed/{id}', [OrderController::class, 'completed'])->middleware('translate')->name('clients-service-orders.completed');
		Route::get('/clients-service-orders/order-finished/{id}', [OrderController::class, 'finished'])->middleware('translate');
		Route::get('/clients-service-orders/cancel/{id}', [OrderController::class, 'cancel'])->middleware('translate');

		//routes for show a list of orders and order details -> FOR SUPER ADMIN & ADMIN USERS
		Route::get('/clients-service-orders', [OrderController::class, 'all'])->middleware('translate');
		Route::get('/client-service-order/details/{id}', [OrderController::class, 'show_client_details'])->middleware('translate');

		//route to send notification to clients
		Route::post('/send-order-notification-toclient/{id}', [OrderNotificationsController::class, 'store_by_management']);

		//Documents Routes -> for ADMINISTRATOR and SUPER ADMINISTRATOR
		Route::get('/documents-repository', [DocumentController::class, 'all'])->middleware('translate')->name('documents.repository');
		Route::get('/documents-repository/download-file/{id}', [DocumentController::class, 'download'])->name('documents.download-management');

		//Services -> for ADMINISTRATOR and SUPER ADMINISTRATOR
		Route::post('/services/status/active/{id}', [ProductController::class, 'active'])->name('services.active');
		Route::post('/services/status/inactive/{id}', [ProductController::class, 'inactive'])->name('services.inactive');

		//routes to add, delete and list services (products)
		Route::resource('services', ProductController::class)->middleware('translate');

		// SUPER ADMINISTRATOR
		Route::resource('settings', SettingController::class)->middleware('translate');

		//CALL CENTER
		Route::get('/clients-management', [NotesController::class, 'index'])->name('clients-management.index')->middleware('translate');
		Route::get('/clients-management/show-client-detail/{id}', [NotesController::class, 'show'])->name('clients-management.show')->middleware('translate');
		Route::post('/clients-management/notes/store/{id}', [NotesController::class, 'store'])->name('clients-management.store');
		Route::delete('/clients-management/delete/{id}', [NotesController::class, 'destroy'])->name('clients-management.destroy')->middleware('translate');

		//notifications
		Route::get('/notifications', [AppNotificationController::class, 'management'])->middleware('translate')->name('notifications.management');
		Route::get('/notifications/update', [AppNotificationController::class, 'management_update'])->middleware('translate')->name('notifications.management-update');

		//Sessions routes for all management users
		Route::get('/login', function () {

			//redirections
			if(Auth::user()->role == "CALL CENTER"){
				return redirect('/management-area/clients-management');
			} else if(Auth::user()->role == "ADMINISTRATOR"){
				return redirect()->route('summery-accounter');
			} else if(Auth::user()->role == "SUPER ADMINISTRATOR"){
				return redirect()->route('summery-admin');
			} else{
				return redirect('/management-area/assigned-service-orders');
			}

		})->middleware('translate')->name('sign-in');

	});
	//routes for login ->FOR ALL MANAGEMENT USERS
	Route::group(['middleware' => 'guest'], function () {
		Route::get('/login', [SessionsController::class, 'create_management']);
		Route::post('/session', [SessionsController::class, 'store_management']);

		Route::get('/login/forgot-password', [ResetController::class, 'createManagement']);
		Route::post('/forgot-password', [ResetController::class, 'sendEmailManagement']);
		Route::get('/reset-password/{token}', [ResetController::class, 'resetPassManagement'])->name('password.reset');
		Route::post('/reset-password', [ChangePasswordController::class, 'changePasswordManagement'])->name('password.update');

	});

});

//logout route
Route::get('/logout', [SessionsController::class, 'destroy'])->middleware('auth'); //general logout
Route::get('/terms-and-conditions', [SettingController::class, 'terms_conditions'])->name('terms_conditions.read'); //general

//language routes for all users
Route::get('/lang/{language}', function($language){
	session()->put('language', $language);
	return redirect()->back();
})->middleware('auth')->name('language');

//redirection to start page
Route::get('/', function(){
	return redirect()->route('login.client');
});
