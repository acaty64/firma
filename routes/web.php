<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/sign', [
	'as' => 'sign',
	'uses' => 'SignController@index'
]);

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/autologin/{id}/{api_token}', [
	'as' => 'autologin',
	'uses' => 'LoginController@autologin'
]);

// Route::get('/login', function()
// {
// 	return "<a href='" . env("APP_URL_USER") . "'>Debe acceder por este enlace.</a>";
// })->name('login');
Route::get('login', [
	'as' => 'login',
	'uses' => 'LoginController@login'
]);

Route::post('/logout', [
	'as' => 'logout',
	'uses' => 'Auth\LoginController@logout'
]);
// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
