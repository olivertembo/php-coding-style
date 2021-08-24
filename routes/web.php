<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdvertisementController;


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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {

    Route::get('/home',                 [            DashboardController::class, 'index']);
    //INSPECTIONS
    Route::get('/advertisements',                    [AdvertisementController::class, 'show'])->name('inspection-show');
    Route::get('/advertisement/{advertisement:uuid}',[AdvertisementController::class, 'show'])->name('inspection-show');
    
    Route::post('/advertisements-add',                [AdvertisementController::class, 'store'])->name('inspection-add');
    Route::post('/advertisements-update',             [AdvertisementController::class, 'update'])->name('inspection-update'); //Edit
    Route::post('/advertisements-delete',             [AdvertisementController::class, 'destroy'])->name('inspection-destroy'); //Delete

});


require __DIR__.'/auth.php';
