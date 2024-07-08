<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;

/** for side bar menu active */
function set_active($route) {
    if (is_array($route )){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/', [HomeController::class, 'index']);

Route::group(['middleware'=>'auth'],function()
{
    Route::get('/admin',function()
    {
        return view('admin.dashboard.home');
    });
    Route::get('/admin',function()
    {
        return view('admin.dashboard.home');
    });
});

Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    // -----------------------------login----------------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::get('logout/page', 'logoutPage')->name('logout/page');
    });

    // ------------------------------ register ----------------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');    
    });

    // ----------------------------- forget password ----------------------------//
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');    
    });

    // ----------------------------- reset password -----------------------------//
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');    
    });
});

Route::group(['namespace' => 'App\Http\Controllers\Admin'],function()
{
    // -------------------------- main dashboard ----------------------//
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/admin', 'index')->middleware('auth')->name('admin');
    });
    Route::controller(SettingController::class)->group(function () {
        Route::get('/admin/setting', 'index')->middleware('auth')->name('setting');
        Route::post('/admin/setting', 'update')->name('settings.update');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/admin/user', 'index')->middleware('auth')->name('user');
        Route::post('/admin/create', 'store')->name('user.store');;
    });
});


