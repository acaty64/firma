<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\C1MController as Controller;
use App\Traits\Imagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Imagick;

class C1MController extends Controller
{
	use Imagenes;

    public function index()
    {
    	$user_id = Auth::user()->id;
    	return view('app.c1m.index')->with(['user_id' => $user_id]);
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
                'file_out' => [
                    'filename' => $new_name,
                    'imagefile' => '/storage/images/out/' . $user_id . '/' . $new_name
                ],
            ];

        $response = $this->preview(new Request($request));

        $fileview = $request['file_out']['imagefile'];
        return view('app.c1m.download')->with(['file' => $fileview]);

    }

}
