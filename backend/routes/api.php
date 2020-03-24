<?php

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

Route::post('/register', 'Auth\RegisterController@create');
Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});
Route::post('/login', 'Auth\LoginController@login');
Route::post('/signup', 'Auth\RegisterController@create');
Route::get('/getVacationImage', 'VacationImageController@getVacationImage');
Route::get('/setWebhook', 'BotController@setWebhook');
Route::post('/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ', 'BotController@vacation');
Route::post('/facebookWebhook', 'BotController@facebookVacation');
