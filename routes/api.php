<?php

use App\Http\Controllers\API\DepartmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\OfficerController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/',[UserController::class,'index']);
// Route::get('/{id}',[UserController::class,'show']);

Route::apiResource('/user',UserController::class);
Route::apiResource('/department',DepartmentController::class);
//search
Route::get('/search/department',[DepartmentController::class,'search']);

Route::apiResource('/officer',OfficerController::class);
