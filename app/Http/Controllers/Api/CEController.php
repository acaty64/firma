<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Imagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Imagick;

class CEController extends Controller
{
	use Imagenes;

	public function uploadPDF(Request $request)
	{
		$user_id = $request->user_id;
		$file_PDF = $request->file_PDF;

		try {
	        $response = $this->pdf2png($user_id, $file_PDF);
	        if($response['filename'] == $request->file_PDF->getClientOriginalName())
	        {
	        	$pages = [];
		        foreach ($response['pages'] as $new_file) {
		            $file = $this->imagePath('png', $user_id) . basename($new_file);
		            $file_out = $this->imagePath('transp', $user_id) . basename($new_file);
		            $response2 = $this->pngTransparent($user_id, $file, $file_out);
		            if($response2)
		            {
		            	$pages[] = $file_out;
		            }
		        }
	        }else{
	        	return 'error, pdf2png';
	        }

		} catch (Exception $e) {
        	return 'error, pngTransparent';
		}

		return [
				'success' => true,
				'pages' => $pages
			];

	}

	public function uploadPhoto(Request $request)
	{
		$user_id = $request->user_id;
		$file_photo = $request->file_photo;
		$originalName = $file_photo->getClientOriginalName();

		$path = $this->imagePath("jpg", $user_id);

		try {
			$pathPhoto = 'images/jpg/' . $user_id;
			$filePhoto = Storage::putFileAs($pathPhoto, $file_photo, $originalName);
			$filepath = $this->imagePath('jpg', $user_id) . $originalName;
		} catch (Exception $e) {
			return ['success'=>false, 'mess'=>'no se grabo archivo ' . $originalName,];
		}

		if(file_exists($filepath)){
			$response = $this->jpg2png($user_id, $filepath);

			if($response){
				$file_in = $filepath;
				$porc = 1 ;
				$fileout = $this->imagePath('png', $user_id) . 'photo.png';
				$response = $this->resizeImagick($filepath, $porc, $fileout);
			}else{
				return [
					'success' => false,
					'message' => 'error, jpg2png or resizeImagick'
				];

			}
		}

		return [
			'success' => true,
			'filepath' => $fileout
		];

	}

	public function preview(Request $request)
	{
		$user_id = $request->user_id;
		$back = $this->imagePath('guide') . 'certificado.jpg';
		$file_pdf = $request->file_out;
		$this->cleanPath($this->imagePath('work', $user_id), 'jpg');
		$this->cleanPath($this->imagePath('pdf', $user_id), 'pdf');
		$this->cleanPath($this->imagePath('out', $user_id), 'pdf');
		$pages_jpg = [];
		foreach ($request['pages'] as $page) {
			$file_in = ['filepath' => $back];
			$file_sign = [
					'filepath' => $page,
					'porc_sign' => 100
				];
			$seccion = 5;
			$posX = 48;
			$posY = 49;
			$file_out = [
					'filepath' => $this->imagePath('work', $user_id) . basename($page, 'png') . 'jpg'
				];

			$this->iAddStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);

			$file_in = ['filepath' => $file_out['filepath']];
			$file_sign = [
					'filepath' => $this->imagePath('png', $user_id) . 'photo.png',
					'porc_sign' => 100
				];
			$seccion = 3;
			$posX = 75;
			$posY = 20;
			$file_out = [
					'filepath' => $this->imagePath('work', $user_id) . basename($page, 'png') . 'jpg'
				];

			$this->iAddStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);
			$pages_jpg[] = $file_out['filepath'];

		}


		$pdf = new \Imagick($pages_jpg);
		$fileout = $this->imagePath('out', $user_id) . $file_pdf['filename'];
		$pdf->setImageFormat('pdf');
		$pdf->writeImages($fileout, true);

	}



}
