<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Imagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Imagick;

class C1MController extends Controller
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

	public function preview(Request $request)
	{
		$user_id = $request->user_id;
		$back = $this->imagePath('guide') . 'Hoja_membretada.jpg';
		$file_pdf = $request->file_out;
		$this->cleanPath($this->imagePath('work', $user_id), 'jpg');
		$this->cleanPath($this->imagePath('pdf', $user_id), 'pdf');
		$this->cleanPath($this->imagePath('out', $user_id), 'pdf');
		$pages_jpg = [];
		foreach ($request['pages'] as $page) {
			$file_in = ['filepath' => $back];
			$file_sign = [
					'filepath' => $page,
					'porc_sign' => 70
				];
			$seccion = 5;
			$posX = 50;
			$posY = 50;
			$file_out = [
					'filepath' => $this->imagePath('work', $user_id) . basename($page, 'png') . 'jpg'
				];

			$this->iAddStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);

			$pages_jpg[] = $file_out['filepath'];

		}

		$new_name = basename($file_pdf['filename'], '.pdf') . '[M].pdf' ;
		$pdf = new \Imagick($pages_jpg);
		$fileout = $this->imagePath('out', $user_id) . $new_name;
		$pdf->setImageFormat('pdf');
		$pdf->writeImages($fileout, true);

		return [
			'success' => true,
			'filepath' => '/storage/images/out/' . $user_id . '/' . $new_name,
			'filename' => $new_name
		];

	}



}
