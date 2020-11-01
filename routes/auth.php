<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::get('/sign', [
	'as' => 'sign',
	'uses' => 'SignController@index'
]);

Route::get('/certificate', [
    'as' => 'certificate',
    'uses' => 'CertificateController@index'
]);

Route::post('/certificate/merge', [
    'as' => 'ce.merge',
    'uses' => 'CertificateController@merge'
]);

Route::get('/c1m', [
    'as' => 'c1m',
    'uses' => 'C1MController@index'
]);

Route::post('/c1m/merge', [
    'as' => 'c1m.merge',
    'uses' => 'C1MController@merge'
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


Route::catch(function()
{
    throw new NotFoundHttpException;
});
