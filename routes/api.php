<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// public route 
Route::post('/register', [
    UserController::class,
    'register'
]);
Route::post('/login', [UserController::class, 'login']);
Route::post('/send_reset_password_email', [PasswordResetController::class, 'send_reset_password_email']);
Route::post('/reset/{token}', [PasswordResetController::class, 'reset']);




// protected route 
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/loged_user', [UserController::class, 'loged_user']);
    Route::post ('/chenge_password', [UserController::class, 'chenge_password']);

});












Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});