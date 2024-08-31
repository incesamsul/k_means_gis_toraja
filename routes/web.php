<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\General;
use App\Http\Controllers\Home;
use App\Http\Controllers\KmeansController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\VikorController;
use App\Http\Controllers\WilayahController;
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



Route::post('/postlogin', [LoginController::class, 'postLogin']);
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/', [Home::class, 'index']);


Route::get('/tentang_aplikasi', [Home::class, 'tentangAplikasi']);


Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
});

Route::group(['middleware' => ['auth', 'ceklevel:Administrator,user']], function () {

    Route::get('/dashboard', [General::class, 'dashboard']);
    Route::get('/profile', [General::class, 'profile']);
    Route::get('/bantuan', [General::class, 'bantuan']);
    Route::post('/update_profile', [General::class, 'updateProfile']);

    Route::get('/kmeans', [KmeansController::class, 'index']);
    Route::get('/pemetaan', [KmeansController::class, 'pemetaan']);
    Route::resource('tanaman', TanamanController::class);
    Route::resource('wilayah', WilayahController::class);

    Route::post('/ubah_foto_profile', [General::class, 'ubahFotoProfile']);
    Route::post('/ubah_role', [General::class, 'ubahRole']);


});

Route::group(['middleware' => ['auth', 'ceklevel:user']], function () {
});


Route::group(['middleware' => ['auth', 'ceklevel:Administrator']], function () {
    Route::group(['prefix' => 'admin'], function () {

        Route::get('/pengguna', [Admin::class, 'pengguna']);
        Route::get('/fetch_data', [Admin::class, 'fetchData']);


        Route::post('/create_pengguna', [Admin::class, 'createPengguna']);
        Route::post('/update_pengguna', [Admin::class, 'updatePengguna']);
        Route::post('/delete_pengguna', [Admin::class, 'deletePengguna']);
    });
});
