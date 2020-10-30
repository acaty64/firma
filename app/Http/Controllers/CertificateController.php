<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\CEController as CEController;
use App\Traits\Imagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Imagick;

class CertificateController extends CEController
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

        $this->cleanPath($this->imagePath('out', $user_id), 'pdf');
        $this->cleanPath($this->imagePath('transp', $user_id), 'png');

        $requestPDF = [
            'user_id' => $user_id,
            'file_PDF' => $request->archivo
        ];
// dd('C1MController@merge', $requestPDF);

        try {
            $responsePDF = $this->uploadPDF(new Request($requestPDF));

            $requestPhoto = [
                'user_id' => $user_id,
                'file_photo' => $request->photo
            ];

            try {
                $responsePhoto = $this->uploadPhoto(new Request($requestPhoto));
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Error uploadPhoto'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error uploadPDF'
            ];
        }


        $request =[
                'user_id' => $user_id,
                'pages' => $responsePDF['pages'],
                'file_photo' => $responsePhoto['filepath'],
                'file_out' => [
                    'filename' => $request->archivo->getClientOriginalName(),
                    'imagefile' => '/storage/images/out/' . $user_id . '/' . $request->archivo->getClientOriginalName()
                ],
            ];

        $response = $this->preview(new Request($request));

        $fileview = $request['file_out']['imagefile'];
        return view('app.certificates.download')->with(['file' => $fileview]);

    }


  //   public function merge(Request $request)
  //   {
  //   	$user_id = $request->user_id;
  //   	$file = $request->file('archivo');
  //   	$oldfilename = $file->getClientOriginalName();

  //   	$response = $this->pdf2png($user_id, $file);

  //   	$seccion = 5;
  //   	$posX = 48;
  //   	$posY = 50;

  //       $path = $this->imagePath("work", $user_id);
  //       $this->cleanPath($path, 'png');

  //   	foreach ($response['pages'] as $key => $value) {
	 //    	$file_in = [
	 //    		'filepath' => $this->pathGuide() . 'CERTIFICADO_fondo.jpg',
	 //    	];
	 //    	$file_sign = [
	 //    		'filepath' => $value,
  //               'porc_sign' => 98
	 //    	];
	 //    	$file_out = [
	 //    		'filepath' => $this->imagePath("work", $user_id) . basename($value, '.png') . '.jpg',
	 //    	];
  //   		$this->addStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);
  //   	}

  //   	$photo_file = $request->file('photo');
  //   	$imagick = new Imagick();

  //       $imagick->setResolution(200, 200);

  //       $imagick->readImage($photo_file);

  //       $pathOut = 'storage/images/work/' . $user_id . '/';
  //       $nameOut = "photo";
  //       $fileout = public_path($pathOut . $nameOut . '.png');
  //       $imagick->writeImages($fileout, true);

  //   	$seccion = 3;
  //   	$posX = 70;
  //   	$posY = 20;

  //   	$files = [];
  //   	foreach ($response['pages'] as $key => $value) {
	 //    	$file_in = [
	 //    		'filepath' => $this->imagePath("work", $user_id) . basename($value, '.png') . '.jpg',
	 //    	];
	 //    	$file_sign = [
	 //    		'filepath' => $this->imagePath("work", $user_id) . $nameOut . '.png',
  //               'porc_sign' => 130
	 //    	];
	 //    	$file_out = [
	 //    		'filepath' => $this->imagePath("work", $user_id) . basename($value, '.png') . '.jpg',
	 //    	];
	 //    	$files[] = [
	 //    			'filename' => basename($value, '.png') . '.jpg'
	 //    		];
  //   		$resp_add = $this->iAddStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);
  //   	}


  //       $resp = $this->jpgToPdf($files, $oldfilename, $user_id);

		// return view('app.certificates.download')->with(['file' => $resp['filepath']]);

  //   }




}
