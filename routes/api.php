<?php

use Illuminate\Http\Request;

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

Route::group([
    'prefix' => 'user',
    'middleware' => 'auth:api'], function() {
   Route::get('{user}', 'UserController@get');
});


Route::group(['prefix' => 'picture'], function() {
    Route::post('', 'PictureController@post');
});

Route::group(['prefix' => 'category'], function() {
    Route::post('', 'CategoryController@post');
    Route::get('{category}', 'CategoryController@get');
});

Route::group(['prefix' => 'directory'], function() {

});

Route::group(['prefix' => 'video'], function() {

});



