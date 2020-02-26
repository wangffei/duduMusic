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
Route::any('/list_music/{id}', "Api\AdminController@list_music");
Route::get('/login', "Api\AdminController@login1");
Route::get('/register', "Api\AdminController@register1");
Route::post('/upload', "Api\AdminController@upload_file1");
Route::get('/commit', "Api\AdminController@commit1");
Route::get('/song_list', "Api\AdminController@song_list1");
Route::get('/add', "Api\AdminController@add1");
Route::get('/delete', "Api\AdminController@delete1");
Route::get('/update_count', "Api\AdminController@update_count1");
Route::get('/delete_list', "Api\AdminController@delete_list1");
Route::get('/delete_lists', "Api\AdminController@delete_lists1");
Route::get('/online_list', "Api\AdminController@online_list1");
Route::get('/update_list', "Api\AdminController@update_list1");
Route::get('/delete_music', "Api\AdminController@delete_music1");
Route::get('/delete_musics', "Api\AdminController@delete_musics1");
Route::get('/update_music', "Api\AdminController@update_music1");
Route::get('/all_users', "Api\AdminController@all_users1");
Route::get('/delete_user', "Api\AdminController@delete_user1");
Route::get('/delete_users', "Api\AdminController@delete_users1");
Route::get('/update_user', "Api\AdminController@update_user1");