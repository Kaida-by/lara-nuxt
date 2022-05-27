<?php

use App\Http\Controllers\api\Auth\LoginController;
use App\Http\Controllers\api\Auth\RegisterController;
use App\Http\Controllers\api\MeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/auth'], function () {
   Route::post('register', [RegisterController::class, 'register']);
   Route::post('login', [LoginController::class, 'login']);
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/me', [MeController::class, 'index']);
    Route::get('/auth/logout', [MeController::class, 'logout']);
});
