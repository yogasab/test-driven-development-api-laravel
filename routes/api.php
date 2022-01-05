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

Route::middleware('auth:sanctum')->group(function () {
  Route::apiResource('todo-lists', 'API\TodoListController');
  Route::apiResource('labels', 'API\LabelController');
  Route::apiResource('todo-list.tasks', 'API\TaskController')->shallow();
  Route::post('/service/callback', 'API\ServiceController@callback')->name('service.callback');
  Route::post('/service/{service}/upload', 'API\ServiceController@upload')->name('service.upload');
});

Route::get('/service/connect/{service}', 'API\ServiceController@connect')->name('service.connect');
Route::post('/register', 'API\Auth\RegisterController')->name('register.user');
Route::post('/login', 'API\Auth\LoginController')->name('login.user');
