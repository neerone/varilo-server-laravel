<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/projects/add', 'ProjectController@add');
Route::get('/projects/delete/{project_id}', 'ProjectController@delete');
Route::get('/projects/{project_id}', 'ProjectController@open');


Route::get('/projects/{project_id}/preview', 'ProjectController@preview');