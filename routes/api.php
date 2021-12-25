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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('todo-lists', 'API\TodoListController@index')->name('todo-lists.index');
Route::get('todo-lists/{list}', 'API\TodoListController@show')->name('todo-lists.show');
Route::post('todo-lists', 'API\TodoListController@store')->name('todo-lists.store');
Route::delete('todo-lists/{list}', 'API\TodoListController@destroy')->name('todo-lists.destroy');
Route::patch('todo-lists/{list}', 'API\TodoListController@update')->name('todo-lists.update');
