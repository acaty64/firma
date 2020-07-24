<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Imagenes;
use Illuminate\Http\Request;

class GuideController extends Controller
{
	use Imagenes;

	public function sign(Request $request)
	{
        $user_id = $request->user_id;

		try {
      $fileSign = $request->file_sign->store('images/view/' . $user_id, 'local');
      $file_in = $this->pathView($user_id) . basename($fileSign);

      $path = $this->pathOriginal($user_id);
      if(!file_exists($path)){
          mkdir($path);
      }else{
          array_map('unlink', glob($path . "*.png"));
      }

      $file_out = $this->pathOriginal($user_id) . basename($fileSign);
      copy($file_in, $file_out);

      if(!file_exists($file_out)){
          return ['success'=> false, 'mess' => 'No se creo el archivo ' . $file_out];
      }

      $path = $this->pathWork($user_id);
      if(!file_exists($path)){
          mkdir($path);
      }else{
          array_map('unlink', glob($path . "*.png"));
      }

      $file_out = $this->pathWork($user_id) . basename($fileSign);

      copy($file_in, $file_out);
      chmod($file_out, 0755);

      if(!file_exists($file_out)){
          return ['success'=> false, 'mess' => 'No se creo el archivo ' . $file_out];
      }

		} catch (Exception $e) {
            return false;			
		}
        
		$filename = basename($fileSign);

    	$file_sign = [ 
    			'filepath' => $this->pathView($user_id) . $filename,
                'filework' => $this->pathWork($user_id) . $filename,
    			'filename' => $filename,
    			'path' => 'images/view/' . $user_id . '/',
    		];

    	if(!file_exists($file_sign['filepath'])){
    		return ['success'=>false, 'error'=>'error GuideController@sign no se encuentra: ' . $file_sign['filepath']];
    	}else{
	    	$stamp = $this->imageFromFile($file_sign['filepath']);
    	}

        $wstamp = imagesx($stamp);
        $hstamp = imagesy($stamp);

        $porc = 100/$wstamp; 
        $porc = 0.3; 

        $stamp = $this->resizeImage($file_sign['filepath'], $porc);
        $this->saveFromImage($stamp, $file_sign['filepath']);

		return $file_sign;
	}

    public function init(Request $request)
    {
        $user_id = $request->user_id;

        if($request->filefirma == 'sign_guide.png'){

            $path = $this->pathView($user_id);
            if(!file_exists($path)){
                mkdir($path);
                chmod($path, 0755);
            }else{
                array_map('unlink', glob($path . "*.png"));
            }

            $path = $this->pathBack($user_id);
            if(!file_exists($path)){
                mkdir($path);
                chmod($path, 0755);
            }else{
                array_map('unlink', glob($path . "*.png"));
                array_map('unlink', glob($path . "*.jpg"));
            }

            $path = $this->pathWork($user_id);
            if(!file_exists($path)){
                mkdir($path);
                chmod($path, 0755);
            }else{
                array_map('unlink', glob($path . "*.png"));
                array_map('unlink', glob($path . "*.jpg"));
            }

            $path = $this->pathOriginal($user_id);
            if(!file_exists($path)){
                mkdir($path);
                chmod($path, 0755);
            }else{
                array_map('unlink', glob($path . "*.png"));
                array_map('unlink', glob($path . "*.pdf"));
            }

            $path = $this->pathOut($user_id);
            if(!file_exists($path)){
                mkdir($path);
                chmod($path, 0755);
            }else{
                array_map('unlink', glob($path . "*.pdf"));
            }
        }
    	$filename = $request->filename;
    	$filename = str_replace('"', '', $filename);
    	$path = 'images/guide/';
    	$file_in = [ 
    			'filepath' => public_path('storage/') . $path . $filename,
    			'filename' => $filename,
    			'path' => $path,
    		];
    	if(!file_exists($file_in['filepath']))
    	{
    		return [
    			'success'=> false, 
    			'mess'=> $file_in['filepath'] . ' file not found',
    		];
    	}

    	$filename = $request->filefirma;
    	$filename = str_replace('"', '', $filename);
    	if($filename == 'sign_guide.png')
    	{
    		$path = 'images/guide/';
            $filepath = public_path('storage/') . $path . $filename;
    	} else {
            $filepath = $this->pathView($user_id) . $filename;
    		$path = 'images/view/' . $user_id . '/';
    	}

    	$file_stamp = [ 
    			'filepath' => $filepath,
    			'filename' => $filename,
    			'path' => $path,
    		];
    	if(!file_exists($file_stamp['filepath']))
    	{
    		return [
    			'success'=> false, 
    			'mess'=> $file_stamp['filepath'] . ' file not found',
    		];
    	}

    	$filename = $request->fileout;
    	$filename = str_replace('"', '', $filename);
    	$path = 'images/view/' . $user_id . '/';
    	$file_out = [ 
    			'filepath' => public_path('storage/') . $path . $filename,
    			'filename' => $filename,
    			'path' => $path,
    		];

    	$seccion = $request->seccion;
    	$posX = $request->horizontal ;
    	$posY = $request->vertical ;

    	$response = $this->addStamp($file_in, $file_stamp, $seccion, $posX, $posY, $file_out);

    	return [
    		'success' => true,
    		'filebase' => $response
    	];
    }

}
