<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Imagick;

trait Imagenes
{
    public function pathGuide()
    {
        return storage_path('app/public/images/guide/');
    }

    public function pathView($user_id)
    {
        return storage_path( 'app/public/images/view/') . $user_id . '/';
    }

    public function pathOriginal($user_id)
    {
        return storage_path('app/public/images/original/') . $user_id . '/';
    }

    public function pathBack($user_id)
    {
        return storage_path('app/public/images/back/') . $user_id . '/';
    }

    public function pathWork($user_id)
    {
        return storage_path('app/public/images/work/') . $user_id . '/';
    }

    public function pathOut($user_id)
    {
        return storage_path('app/public/images/out/') . $user_id . '/';
    }

    public function addStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out)
    {

        $file_in = $file_in['filepath'];
        $file_stamp = $file_sign['filepath'];

        if(!file_exists($file_in)){
            return false;
        }
        $img = $this->imageFromFile($file_in);

        $stamp = $this->imageFromFile($file_stamp);
        if(array_key_exists('porc_sign',$file_sign)){
            $stamp = $this->resizeImage($file_stamp, $file_sign['porc_sign']/100);
        }

        $wstamp = imagesx($stamp);
        $hstamp = imagesy($stamp);

        // dd($wstamp, $hstamp);

        // Tamaño   72 PPI      300 PPI      150 PPI
        // A4       595 x 842   2480 x 3508  1240 x 1754

        // PPI 120
        $TaxisX = 992;
        $TaxisY = 1403;
        // PPI ????
        $TaxisX = imagesx($img);
        $TaxisY = imagesy($img);
        $px = $TaxisX/3;
        $py = $TaxisY/3;

        $secciones = [
            1 => [0 * $px, 0 * $py],
            2 => [1 * $px, 0 * $py],
            3 => [2 * $px, 0 * $py],
            4 => [0 * $px, 1 * $py],
            5 => [1 * $px, 1 * $py],
            6 => [2 * $px, 1 * $py],
            7 => [0 * $px, 2 * $py],
            8 => [1 * $px, 2 * $py],
            9 => [2 * $px, 2 * $py],
        ];

        $axisX = $secciones[$seccion][0] + (($posX/100)*($px - $wstamp));
        $axisY = $secciones[$seccion][1] + (($posY/100)*($py - $hstamp));
        imagecopy($img, $stamp, $axisX, $axisY,
         0, 0, $wstamp, $hstamp);

        //se copia la imagen
        imagejpeg($img, $file_out['filepath'], 100);
// dd('imagenes.addStamp', [$img, $stamp, $axisX, $axisY, $wstamp, $hstamp]);

        return $file_out;
    
    }


    public function resizeImage($filepath, $porc)
    {
         // Fichero y nuevo tamaño
        // $nombre_fichero = $filepath;
        // $porcentaje = $porc;

        // Tipo de contenido
        // header('Content-Type: image/jpeg');

        // Obtener los nuevos tamaños
        // list($ancho, $alto) = getimagesize($filepath);
        
        $imagen = $this->imageFromFile($filepath);

        $ancho = imagesx($imagen);
        $alto = imagesy($imagen);

        $nuevo_ancho = $ancho * $porc;
        $nuevo_alto = $alto * $porc;

        // Cargar
        $thumb = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
        $origen = $this->imageFromFile($filepath);

        header('Content-Type: image/png');
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);

        // Cambiar el tamaño
        imagecopyresized($thumb, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

        return $thumb;
        // Imprimir
        // imagejpeg($thumb);       
    }

    public function saveFromImage($image, $fileout)
    {
// dd($image);
        $puntos=explode(".", $fileout);
        $extensionimagenorig=$puntos[count($puntos)-1];

        if (preg_match("/jpg|jpeg|JPG|JPEG/", $extensionimagenorig)) 
        {
            header('Content-Type: image/jpeg');
            $imgm = imagejpeg($image, $fileout);

        }
        if (preg_match("/png|PNG/", $extensionimagenorig)) 
        {
            header('Content-Type: image/png');
            imagealphablending($image, false);
            imagesavealpha($image, true);
////////////////            
            // $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
            // imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $transparent);
///////////////
            $original = $this->imageFromFile($fileout);
            imagecopyresampled($image, $original, 0, 0, 0, 0, imagesx($image), imagesy($image), imagesx($original), imagesy($original));
            $imgm = imagepng($image, $fileout);

        }
        if (preg_match("/gif|GIF/", $extensionimagenorig)) {
            header('Content-Type: image/gif');
            $imgm = imagegif($image, $fileout);
        }

        if(!$imgm)
        {
            return false;
        }

        return $imgm;        
    }

    public function imageFromFile($file)
    {
        $puntos=explode(".", $file);

        $extensionimagenorig=$puntos[count($puntos)-1];

        if (preg_match("/jpg|jpeg|JPG|JPEG/", $extensionimagenorig)) 
        {
            $imgm=imagecreatefromjpeg($file);

        }
        if (preg_match("/png|PNG/", $extensionimagenorig)) 
        {
            if(file_exists($file))
            {
                $imgm=imagecreatefrompng($file);
            }else{
                return false;
                dd('not found: ' . $file);
            }
        }
        if (preg_match("/gif|GIF/", $extensionimagenorig)) {
            $imgm=imagecreatefromgif($file);
        }

        if(!$imgm)
        {
            return false;
        }

        return $imgm;
    }

    public function jpgToPdf($files, $oldfilename, $user_id)
    // public function jpgToPdf(Request $request)
    {
        // $user_id = $request['user_id'];
        // $files = $request['files'];
        // $oldfilename = $request['oldfilename'];
        // dd($request['files']);
        // foreach ($files as $file) {
        //  $img = new Imagick($file);
        //  $img->setImageCompressionQuality(25);
        //  $img->writeImages($file, false);
        // }

        $path = $this->pathOut($user_id);
        if(!file_exists($path)){
            mkdir($path);
        }else{
            array_map('unlink', glob($path . "*.pdf"));
        }

        $parte = explode(".", $oldfilename);
        // echo $parte[0]; // nombre del archivo
        // echo $parte[1]; // extension del archivo

        $namefile = basename($oldfilename, ".pdf");
        $namefile = str_replace(' ', '_', $namefile);
        $namefile = str_replace(',', '', $namefile);

        $newfile = $this->pathOut($user_id) . $namefile . '_firmado.pdf';
        // $newfile = public_path('storage/images/out/') . $namefile . '_firmado.pdf';

        $file_img = [];
        foreach ($files as $file) {
            $file_storage = 'storage/images/work/' . $user_id . '/' . basename($file);
            $file_img[] = $file_storage;
        }       

        $data = [
            'originalName' => $oldfilename,
            'images' => $file_img,
        ];

        $pdf = \PDF::loadView('pdf.pdfoutfile', $data);

        $namefile = basename($newfile);
        $newfilename = 'images/out/' . $user_id . '/' . $namefile;

        $check = Storage::put($newfilename, $pdf->output());

        $newfilename = '/storage/images/out/' . $user_id . '/' . $namefile;
        if(!$check)
        {
            return false;
        }

        return [
                'success' => true,
                'filepath' => $newfilename,
                'filename' => basename($newfilename),
            ];

    }
    
    public function convert(Request $request)
    {
        $originalName = $request->file('pdf')->getClientOriginalName();
        
        $localFile = $request->pdf->store('/tmp', 'local');
        // $imagick = new Imagick();

        $srcimg = storage_path() . '/app/public/' . $localFile;
        if(file_exists($srcimg)){
            $fileNameJpeg = substr($originalName, 0, strlen($originalName)-4) . '.jpeg';
            $targetimg = storage_path() . '/app/public/tmp/' . $fileNameJpeg;

            $im = new imagick();
            $im->setResolution(300, 300);
            $im->readImage($srcimg);
            $im->setImageFormat('jpeg');
            $im->setImageCompression(imagick::COMPRESSION_JPEG); 
            $im->setImageCompressionQuality(100);
            $im->writeImages($targetimg, false);
            unlink($srcimg);
            // $this->addStamp($targetimg . "-0");
            $response = $this->addStamp($fileNameJpeg);
        
            return view('app.image.index');
        }
    
        return false;
    }

}
