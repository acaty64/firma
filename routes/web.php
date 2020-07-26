<?php

use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
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


Route::get('/pdf', function()
{

	$pages = [
            "/storage/images/work/1/page-0.jpg",
            "/storage/images/work/1/page-1.jpg",
            "/storage/images/work/1/page-2.jpg",
            "/storage/images/work/1/page-3.jpg",
            "/storage/images/work/1/page-4.jpg",
            "/storage/images/work/1/page-5.jpg",
            "/storage/images/work/1/page-6.jpg",
            "/storage/images/work/1/page-7.jpg",
            "/storage/images/work/1/page-8.jpg",
            "/storage/images/work/1/page-9.jpg",
            ];

    $pdf = \PDF::loadView('pdf.pdfoutfile', ['images'=>$pages]);
    return $pdf->stream();
return view('pdf.pdfoutfile', ['images'=>$pages]);
return $_SESSION;


} );





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
