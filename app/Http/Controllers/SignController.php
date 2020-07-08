<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignController extends Controller
{
	public function index()
	{
		$user_id = \Auth::user()->id;
		return view('app.sign')
				->with(['user_id'=>$user_id]);
	}
}
