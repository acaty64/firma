<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/guide', [
	'as' => 'guide.init',
	'uses' => 'Api\GuideController@init'
]);

// Route::post('/guide/sign', function(Request $request)
// {
// 	return "route";
// });

Route::post('/guide/sign', [
	'as' => 'guide.sign',
	'uses' => 'Api\GuideController@sign'
]);

Route::post('/doc/saveBack', [
	'as' => 'doc.saveBack',
	'uses' => 'Api\DocController@saveBack'
]);

Route::post('/doc/preview', [
	'as' => 'doc.preview',
	'uses' => 'Api\DocController@preview'
]);

Route::post('/ce/uploadPhoto', [
	'as' => 'ce.uploadPhoto',
	'uses' => 'Api\CEController@uploadPhoto'
]);

Route::post('/ce/uploadPDF', [
	'as' => 'ce.uploadPDF',
	'uses' => 'Api\CEController@uploadPDF'
]);

Route::post('/ce/preview', [
	'as' => 'ce.preview',
	'uses' => 'Api\CEController@preview'
]);

