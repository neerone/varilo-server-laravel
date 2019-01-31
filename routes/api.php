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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/projects/{id}', 'ApiController@projectGet')->middleware('auth:api');
Route::post('/projects/{id}', 'ApiController@projectSave')->middleware('auth:api');
Route::post('/projects/{id}/filesUpload', 'ApiController@filesUpload')->middleware('auth:api');
Route::post('/projects/{id}/loadFile', 'ApiController@loadFile')->middleware('auth:api');
Route::post('/projects/{id}/changeFile', 'ApiController@changeFile')->middleware('auth:api');




/*
Route::group([
	"middleware" => ["auth:api"]
],function () {
	Route::get('/projects/{id}', 'ApiController@apiGet');
});

*/
