<?php

namespace App\Http\Controllers;

use App\Traits\Imagenes;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
	use Imagenes;

	public function index()
	{
		$user_id = \Auth::user()->id;
		return view('app.tools.index')
				->with(['user_id'=>$user_id]);
	}

    public function _pdf2jpg(Request $request)
    {
    	$user_id = $request->user_id;
    	$file = $request->file('archivo');
    	$response = $this->pdf2jpg($user_id, $file);
    	$filepath = $this->pathOut($user_id) . basename($response['filepath']);
		return view('app.tools.downloadJpg')->with(
			[
				'file' => $response['filepath'],
				'fileimg' => $filepath
			]
		);
    }

    public function _resizejpg(Request $request)
    {
    	$user_id = $request->user_id;
    	$file = $request->file('file_jpg');
    	$response = $this->jpg2a4($user_id, $file);
    	$filepath = $this->pathOut($user_id) . basename($response['filepath']);
		return view('app.tools.downloadJpg')->with(
			[
				'file' => $response['filepath'],
				'fileimg' => $filepath
			]
		);
    }

}
