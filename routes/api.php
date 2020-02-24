<?php

use Illuminate\Http\Request ;
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

Route::middleware('auth:api')->get('/user',function (Request $request) {
	return $request->user();
});

Route::get('/music/{id}', "Api\HelloController@index");
Route::get('/albums', "Api\HelloController@albums");
Route::get('/playUrl/{id}', "Api\HelloController@playUrl");
Route::get('/album/{id}', "Api\HelloController@album");
Route::get('/hot', "Api\HelloController@hot");
Route::get('/list', "Api\HelloController@list");
Route::get('/search/{key}/{page}', "Api\HelloController@search");

Route::get("/info", function (Request $request) {
	return phpinfo();
});
Route::get('/userinfo/{username}', "Api\HelloController@getuserinfo");
Route::any('/my_music', "Api\AdminController@data");
Route::get('/login', "Api\AdminController@login1");
Route::get('/register', "Api\AdminController@register1");
Route::post('/upload', "Api\AdminController@upload_file1");
Route::get('/commit', "Api\AdminController@commit1");
Route::get('/song_list', "Api\AdminController@song_list1");