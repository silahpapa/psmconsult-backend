<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\AuthController;
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
Route::controller(\App\Http\Controllers\Api\AuthController::class)->group(function(){
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
});
Route::get('/login/{user_id}/{expires_at}', [AuthController::class, 'loginWithLink'])
    ->name('login');
Route::group(['middleware' => ['jwt.verify']], function() {
    $authController = \App\Http\Controllers\Api\AuthController::class;
    Route::post('/auth/logout', [$authController, 'logout']);
    Route::get('/auth/user', [$authController, 'loggedInUser']);
});
