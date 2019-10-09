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

/*Route::get('/', function () {
    return view('tabla');
});*/

Route::get('/', 'UsersController@lista') -> name('lista');
Route::post('/deletelista', 'UsersController@delete') -> name('borrarCampo');