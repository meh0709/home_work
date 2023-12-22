<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

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
Route::middleware('guest')->group(function (){
    Route::view('/register', 'auth.register')->name('auth.register');
    Route::view('/login', 'auth.login')->name('auth.login');
    Route::view('/verify', 'auth.verify')->name('auth.verify');

    Route::middleware('verify.request.limit:0,3,5')
        ->post('/register', [RegisterController::class, 'create'])->name('auth.register.create');
    Route::middleware('verify.request.limit:0,3,5')
        ->post('/login', [LoginController::class, 'loginUser'])->name('auth.login.user');
    Route::middleware('verify.request.limit:1')
        ->post('/verify', [VerifyController::class, 'verifyCode'])->name('auth.verify.code');
});

Route::middleware('auth')->prefix('cabinet')->group(function (){
    Route::view('/', 'cabinet.index')->name('cabinet.index');
    Route::get('/logout', function (){
        \Illuminate\Support\Facades\Auth::logout();
        return redirect()->route('auth.login');
    })->name('cabinet.logout');
});

