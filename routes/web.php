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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sign', function()
{
	return view('app.sign')->with(['user_id'=>1]);
});


Route::get('/login', function()
{
	return "<a href='" . env("APP_URL_USER") . "'>Debe acceder por este enlace.</a>";
})->name('login');

Route::post('/logout', [
	'as' => 'logout',
	'uses' => 'Auth\LoginController@logout'
]);