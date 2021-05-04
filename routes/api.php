<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get ('/message', [MessageController::class, 'index']);

Route::post ('/message', [MessageController::class, 'store']);

Route::get ('/message/{id}', [MessageController::class, 'show']);

Route::put ('/message/{id}', [MessageController::class, 'update']);

Route::delete ('/message/{id}', [MessageController::class, 'destroy']);

Route::get ('/chat', [ChatController::class, 'index']);

Route::post ('/chat', [ChatController::class, 'store']);

Route::get ('/chat/{id}', [ChatController::class, 'show']);

Route::put ('/chat/{id}', [ChatController::class, 'update']);

Route::delete ('/chat/{id}', [ChatController::class, 'destroy']);