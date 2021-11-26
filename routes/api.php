<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataEtexController;
use App\Http\Controllers\UserInfoController;

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


/***  Register and login user  ***/
Route::post('/register-or-login', [UserInfoController::class, 'registerOrLoginUser']);


/***  get data users  ***/
Route::get('/user-register', [UserInfoController::class, 'getUserRegister']);
Route::get('/user-login', [UserInfoController::class, 'getUserLogin']);


/***  Register data scenes and events click  ***/
Route::post('/register-scene', [DataEtexController::class, 'registerSceneVisit']);
Route::post('/register-click', [DataEtexController::class, 'registerClick']);
Route::post('/register-wall', [DataEtexController::class, 'registerWall']);
Route::post('/register-type-wall', [DataEtexController::class, 'registerTypeWall']);
Route::post('/register-file', [DataEtexController::class, 'registerFile']);
Route::post('/register-file-model', [DataEtexController::class, 'registerFileModel']);

/***  get data scenes, walls and files  ***/
Route::get('/get-list-scenes', [DataEtexController::class, 'getScenesVisit']);
Route::get('/get-list-cliks', [DataEtexController::class, 'getEventClicks']);
Route::get('/get-list-walls', [DataEtexController::class, 'getWalls']);
Route::get('/get-list-type-walls', [DataEtexController::class, 'getTypeWalls']);
Route::get('/get-list-files', [DataEtexController::class, 'getFiles']);
Route::get('/get-list-files-model', [DataEtexController::class, 'getFilesModel']);

Route::get('/register-var', [DataEtexController::class, 'registerVar']);
Route::get('/update-var', [DataEtexController::class, 'updateVar']);
Route::get('/get-var', [DataEtexController::class, 'getVar']);