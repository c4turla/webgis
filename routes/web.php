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
Route::get('/filter-kategori/{id}', [HomeController::class, 'getLokasiByKategori']);
Route::get('/detail-tempat/{id}', [HomeController::class, 'detail']);

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
    Route::controller(FotoController::class)->group(function () {
        Route::delete('/admin/foto/{id}', 'destroy')->middleware('auth')->name('foto.destroy');
        Route::post('/admin/foto/{id}/is-utama', 'updateIsUtama')->middleware('auth')->name('foto.updateIsUtama');
    });
    Route::controller(WisataController::class)->group(function () {
        Route::get('/admin/wisata', 'index')->middleware('auth')->name('wisata');
        Route::get('/admin/wisata/tambah', 'create')->middleware('auth')->name('wisata.add');
        Route::post('/admin/wisata', 'store')->name('wisata.store');
        Route::get('/admin/wisata/{id}/edit', 'edit')->name('wisata.edit');
        Route::put('/admin/wisata/{id}', 'update')->middleware('auth')->name('wisata.update');
        Route::delete('/admin/wisata', 'destroy')->name('wisata.destroy');
    });

    Route::controller(FasilitasOlahragaController::class)->group(function () {
        Route::get('/admin/fasilitas-olahraga', 'index')->middleware('auth')->name('fasilitas-olahraga');
        Route::get('/admin/fasilitas-olahraga/tambah', 'create')->middleware('auth')->name('fasilitas-olahraga.add');
        Route::post('/admin/fasilitas-olahraga', 'store')->name('fasilitas-olahraga.store');
        Route::get('/admin/fasilitas-olahraga/{id}/edit', 'edit')->name('fasilitas-olahraga.edit');
        Route::put('/admin/fasilitas-olahraga/{id}', 'update')->middleware('auth')->name('fasilitas-olahraga.update');
        Route::delete('/admin/fasilitas-olahraga', 'destroy')->name('fasilitas-olahraga.destroy');
    }); 


    Route::controller(SaranaPemudaController::class)->group(function () {
        Route::get('/admin/sarana-pemuda', 'index')->middleware('auth')->name('sarana-pemuda');
        Route::get('/admin/sarana-pemuda/tambah', 'create')->middleware('auth')->name('sarana-pemuda.add');
        Route::post('/admin/sarana-pemuda', 'store')->name('sarana-pemuda.store');
        Route::get('/admin/sarana-pemuda/{id}/edit', 'edit')->name('sarana-pemuda.edit');
        Route::put('/admin/sarana-pemuda/{id}', 'update')->middleware('auth')->name('sarana-pemuda.update');
        Route::delete('/admin/sarana-pemuda', 'destroy')->name('sarana-pemuda.destroy');
    }); 

    Route::controller(SettingController::class)->group(function () {
        Route::get('/admin/setting', 'index')->middleware('auth')->name('setting');
        Route::post('/admin/setting', 'update')->name('settings.update');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/admin/user', 'index')->middleware('auth')->name('user');
        Route::post('/admin/create', 'store')->name('user.store');
    });
});


