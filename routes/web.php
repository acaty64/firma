<?php

use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;


Route::get('/sign', [
	'as' => 'sign',
	'uses' => 'SignController@index'
]);

Route::get('/', function () {
    return redirect('/login');
});

if(env('APP_DEBUG')){
    Auth::routes();
}else{

    Route::get('/autologin/{id}/{api_token}', [
    	'as' => 'autologin',
    	'uses' => 'LoginController@autologin'
    ]);

    Route::get('login', [
    	'as' => 'login',
    	'uses' => 'LoginController@login'
    ]);

    Route::post('/logout', [
    	'as' => 'logout',
    	'uses' => 'Auth\LoginController@logout'
    ]);
}

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/access/index', [
    'as' => 'access.index',
    'uses' => 'AccessController@index'
]);

Route::post('/access/store', [
    'as' => 'access.store',
    'uses' => 'AccessController@store'
]);

Route::get('/access/destroy/{id}', [
    'as' => 'access.destroy',
    'uses' => 'AccessController@destroy'
]);

Route::get('/certificate', [
    'as' => 'certificate',
    'uses' => 'CertificateController@index'
]);

Route::post('/certificate/merge', [
    'as' => 'merge',
    'uses' => 'CertificateController@merge'
]);

Route::get('/tools', [
    'as' => 'tools',
    'uses' => 'ToolsController@index'
]);

Route::post('/tools/pdf2jpg', [
    'as' => 'pdf2jpg',
    'uses' => 'ToolsController@_pdf2jpg'
]);

Route::post('/tools/resizejpg', [
    'as' => 'resizejpg',
    'uses' => 'ToolsController@_resizejpg'
]);

Route::get('/checkPHP', function()
{
    return view('checkPHP');
});

Route::get('/checkPDF', function()
{
    $user_id = "x";

    $file = 'storage/images/guide/certificado.jpg';

    return view('pdf.pdfoutfile', ['file'=>$file]);
});