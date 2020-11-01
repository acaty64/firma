<?php

use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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

// Route::catch(function()
// {
//     throw new NotFoundHttpException;
// });