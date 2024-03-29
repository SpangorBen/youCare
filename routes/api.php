<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// Organizer
Route::get('/annonce', [AnnonceController::class, 'index']);
// Route::middleware('auth:api', 'role:organizer')->group(function () {
    Route::get('/annonce/{id}', [AnnonceController::class, 'show']);
    Route::post('/annonce', [AnnonceController::class, 'store']);
    Route::put('/annonce/{id}', [AnnonceController::class, 'update']);
    Route::delete('/annonce/{id}', [AnnonceController::class, 'destroy']);
    Route::put('/organizer/applications/{id}', [ApplicationController::class, 'updateStatus']);
    Route::get('/organizer/applications', [ApplicationController::class, 'index']);
// });

//Volunteer
    Route::get('/annonces/filter', [AnnonceController::class, 'filter']);
Route::middleware('auth:api', 'role:volunteer')->group(function () {
    Route::post('/user/apply', [ApplicationController::class, 'apply']);
    Route::get('/user/applications', [ApplicationController::class, 'userApplications']);
});

// Admin
Route::middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/users', [AdminController::class, 'index']);
    Route::post('/users', [AdminController::class, 'store']);
    Route::get('/users/{id}', [AdminController::class, 'show']);
    Route::put('/users/{id}', [AdminController::class, 'update']);
    Route::delete('/users/{id}', [AdminController::class, 'destroy']);
});
