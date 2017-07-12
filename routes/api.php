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
   Route::post('{user}/facebook-login', 'Auth\FacebookController@postLogin');
   Route::get('{user}/facebook-status', 'Auth\FacebookController@getStatus');
});


Route::group([
    'prefix' => 'picture',
    'middleware' => 'auth:api'], function() {
    Route::post('', 'PictureController@post');
    Route::put('{picture}', 'PictureController@put');
    Route::delete('{picture}', 'PictureController@delete');
    Route::patch('{picture_id}', 'PictureController@patch');
    Route::delete('force/{picture_id}', 'PictureController@deleteForce');
    Route::get('link-exists', 'PictureController@getLinkExists');
    Route::post('{picture}/facebook/{category}', 'PictureController@postFacebook');
});
Route::group(['prefix' => 'category'], function() {
    Route::get('', 'CategoryController@getAll');
    Route::post('', 'CategoryController@post');
    Route::get('newest', 'CategoryController@getNewest');
    Route::get('{category}', 'CategoryController@get');
    Route::get('link/{category_link}', 'CategoryController@get');

    Route::put('{category}', 'CategoryController@put');
    Route::delete('{category}', 'CategoryController@delete');
    Route::patch('{category_id}', 'CategoryController@patch');
    Route::delete('force/{category_id}', 'CategoryController@deleteForce');
});

Route::group(['prefix' => 'directory'], function() {
    Route::get('', 'DirectoryController@getAll');
    Route::post('', 'DirectoryController@post');
    Route::get('newest', 'DirectoryController@getNewest');
    Route::get('{directory}', 'DirectoryController@get');

    Route::put('{directory}', 'DirectoryController@put');
    Route::delete('{directory}', 'DirectoryController@delete');
    Route::patch('{directory_id}', 'DirectoryController@patch');
    Route::delete('force/{directory_id}', 'DirectoryController@deleteForce');
});

Route::group(['prefix' => 'video'], function() {

});



