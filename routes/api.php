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

// Auth API
Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Auth'], function() {
    Route::post('signin', 'SignInController');
    Route::post('signout', 'SignOutController');
    Route::post('signup', 'SignUpController');
    Route::get('me', 'MeController');
});

// Word API
Route::get('/words', 'App\Http\Controllers\WordController@index');
Route::get('/words/{id}', 'App\Http\Controllers\WordController@show');

// Definition API
Route::get('/definitions', 'App\Http\Controllers\DefinitionController@index');
Route::get('/definitions/{id}', 'App\Http\Controllers\DefinitionController@show');
Route::post('/definitions', 'App\Http\Controllers\DefinitionController@store')->middleware('auth');
Route::post('/definitions/{id}', 'App\Http\Controllers\DefinitionController@delete')->middleware('auth');
Route::post('/definitions/{id}/like', 'App\Http\Controllers\UserController@like');
Route::post('/definitions/{id}/dislike', 'App\Http\Controllers\UserController@dislike');

// Tags API
Route::get('/tags', 'App\Http\Controllers\TagController@index');

// Users API
Route::get('/users', 'App\Http\Controllers\UserController@index');
Route::get('/users/{id}', 'App\Http\Controllers\UserController@show');
Route::get('/users', 'App\Http\Controllers\UserController@index');

// Transverse API
Route::get('/users/{id}/definitions', 'App\Http\Controllers\UserController@userDefinitions');
Route::get('/tags/{id}/definitions', 'App\Http\Controllers\TagController@userDefinitions');

// Comments API
Route::get('/comments', 'App\Http\Controllers\UserController@index');
Route::get('/comments/{id}', 'App\Http\Controllers\UserController@show');

