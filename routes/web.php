<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\MobileController;

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

Route::get('/manifest.json', function () {
    return response()->view('manifest')->header('Content-Type', 'application/json');
});


//This is the actual production one
if (config('app.env') === 'production') {
    Route::domain(config('app.domains.app'))->group(function () {
        Route::get('/', [AppController::class, 'index'])->name('appLogin');
    });

    Route::domain(config('app.domains.mobile'))->group(function () {
        Route::get('/{randomNo}', [MobileController::class, 'index'])->name('mobile');
    });

    Route::domain(config('app.domains.ref'))->group(function () {
        Route::get('/', [AppController::class, 'index'])->name('refLogin');
    });
} else if (config('app.env') === 'local') {
    Route::get('/app', [AppController::class, 'index'])->name('appLogin');
    Route::get('/mobile/{randomNo}', [MobileController::class, 'index'])->name('mobile');
    Route::get('/ref', [AppController::class, 'index'])->name('refLogin');
}

/** start of website exposed */
Route::get('/', [HomeController::class, 'index']);
/* end of website mileage  */

