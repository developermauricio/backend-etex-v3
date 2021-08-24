<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataEtexController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/set-points', 'Controller@setPoints')->name('api.set.points');
Route::get('/get-points-of-user/{email}', 'GetController@getPointsOfUser');
*/

Route::get('/is-auth', [DataEtexController::class, 'isAuth']);
