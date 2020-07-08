<?php

namespace App\Http\Controllers;

use App\Access;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

	public function autologin($id, $api_token)
	{
		try {
// dd(\Config::get('database.connections.mysql_user.username'));
		    $appUser = User::findOrFail($id);

		    if($appUser != null && $api_token == $appUser->refresh_token){

		    	$access = Access::where('user_id', $appUser->id)->first();
		    	if($access){
	        		Auth::login($appUser);
		    		return redirect(route('sign'));

		    	}else{
		    		return view('app.unauthorizated');
		    		return 'logueado no autorizado';
		    	}
		    }else{
		    	return view('app.unauthorizated');
		    }

		} catch (Exception $e) {
			Auth::logout();
	    	return view('app.unauthorizated');
		}
	}

	public function handle($token)
	{
		$appUser = \Auth::user();
	    if($appUser != null && $token == $appUser->refresh_token){

	    	$access = Access::where('user_id', $appUser->id)->first();
	    	if($access){

	    		return redirect(route('sign'));

	    	}else{
	    		return view('app.unauthorizated');
	    		return 'logueado no autorizado';
	    	}
	    }else{
	    	return view('app.unauthorizated');
	    	return redirect('/login');
	    	return 'no logueado';
	    }

	}	

}
