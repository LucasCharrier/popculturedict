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
Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Auth'], function() {
    Route::post('signin', 'SignInController');
    Route::post('signout', 'SignOutController');
    Route::post('signup', 'SignUpController');
    Route::get('me', 'MeController');
});
Route::get('/players', 'App\Http\Controllers\PlayerController@index');
Route::get('/players/{id}', 'App\Http\Controllers\PlayerController@show');
Route::post('/players', 'App\Http\Controllers\PlayerController@store');
Route::post('/players/{id}/answers', 'App\Http\Controllers\PlayerController@answer');
Route::delete('/players/{id}', 'App\Http\Controllers\PlayerController@delete');
Route::delete('/players/{id}/answers', 'App\Http\Controllers\PlayerController@resetAnswers');
Route::get('/words', 'App\Http\Controllers\WordController@index');
Route::get('/words/{id}', 'App\Http\Controllers\WordController@show');

Route::get('/definitions', 'App\Http\Controllers\DefinitionController@index');
Route::get('/definitions/{id}', 'App\Http\Controllers\DefinitionController@show');
Route::post('/definitions', 'App\Http\Controllers\DefinitionController@store')->middleware('auth');
Route::post('/definitions/{id}', 'App\Http\Controllers\DefinitionController@delete')->middleware('auth');

Route::get('/tags', 'App\Http\Controllers\TagController@index');

Route::get('/users', 'App\Http\Controllers\UserController@index');
Route::get('/users/{id}', 'App\Http\Controllers\UserController@show');
Route::get('/users', 'App\Http\Controllers\UserController@index');
Route::get('/users/{id}/definitions', 'App\Http\Controllers\UserController@userDefinitions');
Route::get('/tags/{id}/definitions', 'App\Http\Controllers\TagController@userDefinitions');


// Route::get('/tags', 'App\Http\Controllers\UserController@index');
// Route::get('/tags/{id}', 'App\Http\Controllers\UserController@show');

Route::get('/comments', 'App\Http\Controllers\UserController@index');
Route::get('/comments/{id}', 'App\Http\Controllers\UserController@show');

