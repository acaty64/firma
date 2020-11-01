<?php

use Illuminate\Support\Facades\Route;

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

