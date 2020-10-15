<?php

namespace App\Http\Controllers;

use App\Traits\Imagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Imagick;

class CertificateController extends Controller
{
	use Imagenes;

    public function index()
    {
    	$user_id = Auth::user()->id;
    	return view('app.certificates.index')->with(['user_id' => $user_id]);
    }

    public function merge(Request $request)
    {
    	$user_id = $request->user_id;
    	$file = $request->file('archivo');
    	$oldfilename = $file->getClientOriginalName();

    	$response = $this->pdf2png($user_id, $file);

    	$seccion = 5;
    	$posX = 48;
    	$posY = 50;

        $path = $this->imagePath("work", $user_id);
        $this->cleanPath($path, 'png');

    	foreach ($response['pages'] as $key => $value) {
	    	$file_in = [
	    		'filepath' => $this->pathGuide() . 'CERTIFICADO_fondo3507.jpg',
	    	];
	    	$file_sign = [
	    		'filepath' => $value,
                'porc_sign' => 98
	    	];
	    	$file_out = [
	    		'filepath' => $this->imagePath("work", $user_id) . basename($value, '.png') . '.jpg',
	    	];
    		$this->addStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);
    	}

    	$photo_file = $request->file('photo');
    	$imagick = new Imagick();

        $imagick->setResolution(200, 200);

        $imagick->readImage($photo_file);

        $pathOut = 'storage/images/work/' . $user_id . '/';
        $nameOut = "photo";
        $fileout = public_path($pathOut . $nameOut . '.png');
        $imagick->writeImages($fileout, true);

    	$seccion = 3;
    	$posX = 70;
    	$posY = 20;

    	$files = [];
    	foreach ($response['pages'] as $key => $value) {
	    	$file_in = [
	    		'filepath' => $this->imagePath("work", $user_id) . basename($value, '.png') . '.jpg',
	    	];
	    	$file_sign = [
	    		'filepath' => $this->imagePath("work", $user_id) . $nameOut . '.png',
                'porc_sign' => 130
	    	];
	    	$file_out = [
	    		'filepath' => $this->imagePath("work", $user_id) . basename($value, '.png') . '.jpg',
	    	];
	    	$files[] = [
	    			'filename' => basename($value, '.png') . '.jpg'
	    		];
    		$resp_add = $this->addStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);
    	}


        $resp = $this->jpgToPdf($files, $oldfilename, $user_id);
    	// $resp = $this->jpgToPdf($files, $oldfilename, $user_id);

// dd('CertificateController@merge', $resp);


		return view('app.certificates.download')->with(['file' => $resp['filepath']]);


    	// return ['CertificateController@png', $file->getClientOriginalName(), $response];

    }




}
