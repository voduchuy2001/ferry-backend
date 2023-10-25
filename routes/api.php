<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FerryController;
use App\Http\Controllers\API\FerryRouteController;
use App\Http\Controllers\API\FerryTripController;
use App\Http\Controllers\API\SeatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('me', [AuthController::class, 'me'])->name('me');
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/ferry-trip', [FerryTripController::class, 'index'])->name('ferry-trip.index');
    Route::post('/ferry-trip', [FerryTripController::class, 'create'])->name('ferry-trip.create');
    Route::put('/ferry-trip/{id}', [FerryTripController::class, 'edit'])->name('ferry-trip.edit');
    Route::delete('/ferry-trip/{id}', [FerryTripController::class, 'delete'])->name('ferry-trip.delete');

    Route::get('/ferry-route', [FerryRouteController::class, 'index'])->name('ferry-route.index');
    Route::post('/ferry-route', [FerryRouteController::class, 'create'])->name('ferry-route.create');
    Route::put('/ferry-route/{id}', [FerryRouteController::class, 'edit'])->name('ferry-route.edit');
    Route::delete('/ferry-route/{id}', [FerryRouteController::class, 'delete'])->name('ferry-route.delete');

    Route::get('/ferry', [FerryController::class, 'index'])->name('ferry.index');
    Route::post('/ferry', [FerryController::class, 'create'])->name('ferry.create');
    Route::put('/ferry/{id}', [FerryController::class, 'edit'])->name('ferry.edit');
    Route::delete('/ferry/{id}', [FerryController::class, 'delete'])->name('ferry.delete');

    Route::get('/seat', [SeatController::class, 'index'])->name('seat.index');
    Route::post('/seat', [SeatController::class, 'create'])->name('seat.create');
    Route::put('/seat/{id}', [SeatController::class, 'edit'])->name('seat.edit');
    Route::delete('/seat/{id}', [SeatController::class, 'delete'])->name('seat.delete');
});
