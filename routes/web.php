<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {

    //advertisementS
    Route::get('/advertisements',                    [AdvertisementController::class, 'index'])->name('advertisements');
    Route::get('/advertisement/{advertisement:uuid}',[AdvertisementController::class, 'show'])->name('advertisement-show');
    Route::get('/advertisements-edit/{advertisement:uuid}',[AdvertisementController::class, 'edit'])->name('advertisement-edit');
    Route::post('/advertisements-edit/{advertisement:uuid}',[AdvertisementController::class, 'update'])->name('advertisementupdate2');

    
    Route::get('/advertisements-add',                [AdvertisementController::class, 'create'])->name('advertisement-add');
    Route::post('/advertisements-add',                [AdvertisementController::class, 'store'])->name('advertisement-store');
    Route::post('/advertisements-update',             [AdvertisementController::class, 'update'])->name('advertisement-update'); //Edit
    Route::post('/advertisements-delete/{advertisement:uuid}',             [AdvertisementController::class, 'destroy'])->name('advertisement-destroy'); //Delete

});


require __DIR__.'/auth.php';
