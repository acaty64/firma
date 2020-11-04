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
    	return view('app.certificates.index')
            ->with(['user_id' => $user_id]);
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

        $new_name = basename($request->archivo->getClientOriginalName(),'.pdf') . "[M].pdf";

        $request =[
                'user_id' => $user_id,
                'pages' => $responsePDF['pages'],
                'file_photo' => $responsePhoto['filepath'],
                'file_out' => [
                    'filename' => $new_name,
                    'imagefile' => '/storage/images/out/' . $user_id . '/' . $new_name
                ],
            ];

        $response = $this->preview(new Request($request));

        $fileview = $request['file_out']['imagefile'];
        return view('app.certificates.download')->with(['file' => $fileview]);

    }


}
