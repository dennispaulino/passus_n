<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/admin/loadxlsx', 'HomeController@loadxlsx');
Route::get('/admin/users', 'HomeController@users');
Route::get('/admin/profile', 'HomeController@profile');
Route::post('/admin/profile', 'HomeController@profile');
Route::get('/admin/warnings', 'HomeController@warnings');
Route::get('/admin/adduser', 'HomeController@adduser');
Route::post('/admin/submitcsv', 'HomeController@submitcsv');
Route::get('/admin/home', 'HomeController@index');
Route::get('/quickstart', 'HomeController@quick');
