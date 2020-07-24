<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Imagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Imagick;

class DocController extends Controller
{
	use Imagenes;

	public function createNewPages(Request $request)
	{
		$user_id = $request->user_id;
		$file_stamp = $request->filefirma;
		$files = $request->fileback['pages'];
		$seccion = $request->seccion;
		$posX = $request->horizontal;
		$posY = $request->vertical;
		$hojas = $request->hojas;
		$range_page = $request->range_page;
		$porc_sign = $request->porc_sign;

		$file_stamp['filepath'] = $this->pathView($user_id) . basename($file_stamp['filepath']);

  	if(!file_exists($file_stamp['filepath']))
  	{
  		return [
  			'success'=> false, 
  			'mess'=> $file_stamp['filepath'] . ' file_stamp not found',
  		];
  	}

    $path = $this->pathWork($user_id);
    if(!file_exists($path)){
        mkdir($path);
    }else{
        array_map('unlink', glob($path . "*.jpg"));
    }

  	$filework = [];
  	foreach ( $files as $key => $file ){
    	if(!file_exists($file))
    	{
    		return [
    			'success'=> false, 
    			'mess'=> $file . ' filepath not found',
    		];
  		}

    	$path = 'images/work/' . $user_id . '/';
    	$file_out = [ 
    			'filepath' => $this->pathWork($user_id) . basename($file, ".jpg") . '.jpg',
    			'filename' => basename($file, ".jpg") . '.jpg',
    			'path' => $path,
    		];
    	array_push($filework, $file_out);

      $back_file = $file;
      $work_file = $file_out['filepath'];

      copy($back_file, $work_file);
      chmod($work_file, 0755);

  	}

		$nhojas = [];
		if ($hojas == "rango"){
			$partes = explode(",", $range_page);
			foreach ($partes as $key => $value) {
				if ( strpos($value, '-') > 0 ){
					$start = substr($value, 0, strpos($value, '-'));
					$end = substr($value, strpos($value, '-')+1, strlen($value)-strpos($value, '-')-1);
					for ($i = $start; $i <= $end; $i++) { 
						array_push($nhojas, $i);
					}
				}else{
					array_push($nhojas, $value);
				}
			}
		}
		if ($hojas == "todas")
		{
			foreach ($files as $key => $value)
			{
				array_push($nhojas, $key+1);
			}
		}

		if ($hojas == "ultima")
		{
			array_push($nhojas, count($files));
		}

		/// Resize
    $original_sign = $this->pathOriginal($user_id) . basename($file_stamp['filepath']);

    $back_sign = $this->pathBack($user_id) . basename($file_stamp['filepath']);

    copy($original_sign, $back_sign);

		$back_sign = [
			'filepath' => $back_sign,
			'porc_sign' => $porc_sign,
		];

		$path = 'images/back/' . $user_id . '/';
		foreach ($nhojas as $value) {
			$file_hoja = $files[$value-1];
	    	$file_in = [ 
	    			'filepath' => $file_hoja,
	    			'filename' => basename($file_hoja),
	    			'path' => $path,
	    		];
	    	$file_out = $filework[$value-1];

			$check = $this->addStamp($file_in, $back_sign, $seccion, $posX, $posY, $file_out);
		}
		return [
			'success' => true, 
			'mess' => 'createNewPages', 
			'fileback' => [
				'filename' => $request->fileback['filename'],
				'pages' => $filework
			]
		];
	}

	public function preview(Request $request)
	{
		$user_id = $request->user_id;
		$check = $this->createNewPages($request);

		if($check['success']){
			$files = [];
			foreach ($check['fileback']['pages'] as $key => $value) {
				array_push($files, $value['filepath']);
			}

			$filename = $check['fileback']['filename'];

			$response = $this->jpgToPdf($files, $filename, $user_id);

			if(!$response)
			{
				return ['success'=>false, 'mess'=>'no se genero el nuevo pdf'];
			}
			return $response;
		}
		return ['success' =>false, 'mess' => 'error DocController@preview', 'check' => $check ];
	}


	/// TODO: Add in file page.jpg Auth::user->id
	public function saveBack(Request $request)
	{
		$user_id = $request->user_id;

		$path = $this->pathBack($user_id);
        if(!file_exists($path)){
            mkdir($path);
        }else{
            array_map('unlink', glob($path . "*.jpg"));
        }

		$path = $this->pathBack($user_id);
        if(!file_exists($path)){
            mkdir($path);
            chmod($path, 0755);
        }else{
            array_map('unlink', glob($path . "*.pdf"));
        }

		$originalName = $_FILES['file_back']['name'];
		try {
			$fileBack = $request->file_back->store('images/back/' . $user_id, 'local');
			$fileBack = $this->pathBack($user_id) . basename($fileBack);
      chmod($fileBack, 0755);
		} catch (Exception $e) {
			return ['success'=>false, 'mess'=>'no se grabo archivo ' . $request->file_back->getClientOriginalName,];
		}
		// create Imagick object
		$imagick = new Imagick();
		// Sets the image resolution
		$imagick->setResolution(200, 200);
		// Reads image from PDF
		$imagick->readImage($fileBack);

		$num_pages_pdf = $imagick->getNumberImages();
		// Writes an image
		$pathOut = 'storage/images/back/' . $user_id . '/';
		$nameOut = "page";
		// $fileout = public_path($pathOut . $nameOut . '.png');
		$fileout = public_path($pathOut . $nameOut . '.jpg');
		$imagick->writeImages($fileout, true);

		$file_back = $request->file_back;
		$npages = $imagick->getNumberImages();
		if($npages > 1)
		{
			for ($x = 0; $x < $npages; $x++ )
			{
				$pages[$x] = public_path($pathOut . $nameOut . '-' . $x . '.jpg');
			}
		} else {
			$pages[0] = public_path($pathOut . $nameOut . '.jpg');
		}

		$fileback = [
			'filename' => $originalName,
			'pages' => $pages,
			'num_pages_pdf' => $num_pages_pdf
		];

		return $fileback;
	}

}
