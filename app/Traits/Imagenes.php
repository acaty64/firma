<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Imagick;
use LynX39\LaraPdfMerger\Facades\PdfMerger;

trait Imagenes
{

  public function imagePath($type, $user_id=null)
  {
    if($user_id == null){
      return storage_path( 'app/public/images/'. $type . '/');
    }else{
      return storage_path( 'app/public/images/'. $type . '/') . $user_id . '/';
    }
  }

  public function jpg2png($user_id, $file)
  {
    try {
      $fileout = $this->imagePath('png', $user_id) . basename($file, 'jpg') . 'png';
      $imagick = new \Imagick($file);
      $imagick->setResolution(200, 200);
      $imagick->writeImages($fileout, true);

      return $fileout;
      // return $imagick;

    } catch (Exception $e) {
      return false;
    }

  }

  public function pdf2png($user_id, $file)
  {
    $path = $this->imagePath("png", $user_id);
    $this->cleanPath($path, 'png');

    $path = $this->imagePath("pdf", $user_id);
    $this->cleanPath($path, 'pdf');

    $originalName = $file->getClientOriginalName();
    try {
      $fileBack = $file->store('images/pdf/' . $user_id, 'local');
      $fileBack = $this->imagePath("pdf", $user_id) . basename($fileBack);
      chmod($fileBack, 0755);
    } catch (Exception $e) {
      return ['success'=>false, 'mess'=>'no se grabo archivo ' . $originalName,];
    }
    $imagick = new \Imagick();

    $imagick->setResolution(200, 200);

    $imagick->readImage($fileBack);

    $num_pages_pdf = $imagick->getNumberImages();

    $pathOut = 'storage/images/png/' . $user_id . '/';
    $nameOut = "page";

    $fileout = public_path($pathOut . $nameOut . '.png');
    $imagick->writeImages($fileout, true);

    $npages = $imagick->getNumberImages();
    if($npages > 1)
    {
      for ($x = 0; $x < $npages; $x++ )
      {
        $pages[$x] = public_path($pathOut . $nameOut . '-' . $x . '.png');
      }
    } else {
      $pages[0] = public_path($pathOut . $nameOut . '.png');
    }

    $files_png = [
      'filename' => $originalName,
      'pages' => $pages,
      'num_pages_pdf' => $num_pages_pdf
    ];

    return $files_png;
  }


  public function iAddStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out)
  {
    $file_in = $file_in['filepath'];
    $file_stamp = $file_sign['filepath'];

    if(!file_exists($file_in)){
      return false;
    }

    $img = new \Imagick($file_in);

    $stamp = new \Imagick($file_stamp);

    if(array_key_exists('porc_sign', $file_sign)){
      // $stamp = $this->resizeImage($file_stamp, $file_sign['porc_sign']/100);
      $this->resizeImagick($file_stamp, $file_sign['porc_sign']/100, $file_stamp);
      $stamp = new \Imagick($file_stamp);
    }

    // $stamp->getImageResolution(300,300);

    $wstamp = $stamp->getImageWidth();
    $hstamp = $stamp->getImageHeight();

    $TaxisX = $img->getImageWidth();
    $TaxisY = $img->getImageHeight();

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

    try {
      $img->compositeImage($stamp, Imagick::COMPOSITE_DEFAULT, $axisX, $axisY, Imagick::CHANNEL_ALPHA);
      $img->writeImage($file_out['filepath']);

      $img->destroy();

      return $file_out;
    } catch (Exception $e) {
      return ['success'=>false, 'mess'=>'no se genero archivo ' . $file_out['filepath'],];
    }

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

    if(array_key_exists('porc_sign', $file_sign)){
      $stamp = $this->resizeImage($file_stamp, $file_sign['porc_sign']/100);
    }

    // $stamp->getImageResolution(300,300);

    $wstamp = imagesx($stamp);
    $hstamp = imagesy($stamp);

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

    try {
      //se copia la imagen
      imageresolution($img, 200);
      imagejpeg($img, $file_out['filepath'], 88);
      return $file_out;
    } catch (Exception $e) {
      return ['success'=>false, 'mess'=>'no se genero archivo ' . $file_out['filepath'],];
    }

  }

  public function resizeImagick($filepath, $porc, $fileout)
  {
    $imagick = new \Imagick($filepath);

    $new_width = $imagick->getImageWidth() * $porc;
    $new_height = $imagick->getImageHeight() * $porc;

    $imagick->resizeImage($new_width, $new_height, imagick::FILTER_LANCZOS, 1);

    $imagick->writeImage($fileout);

    $imagick->destroy();

    return $fileout;

  }

  public function resizeImage($filepath, $porc)
  {
    $imagen = $this->imageFromFile($filepath);
    $ancho = imagesx($imagen);
    $alto = imagesy($imagen);

    $nuevo_ancho = $ancho * $porc;
    $nuevo_alto = $alto * $porc;

    $origen = $this->imageFromFile($filepath);

        // Cargar
    $thumb = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

    header('Content-Type: image/png');
    imagealphablending($thumb, false);
    imagesavealpha($thumb, true);

        // Cambiar el tamaño
    imagecopyresized($thumb, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

    return $thumb;
  }

  public function saveFromImage($image, $fileout)
  {
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
      $imgm=@imagecreatefromjpeg($file);

    }
    if (preg_match("/png|PNG/", $extensionimagenorig))
    {
      if(file_exists($file))
      {
        $imgm=imagecreatefrompng($file);
      }else{
        return false;
        dd('not found                                      : ' . $file);
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
  {
    $path = $this->imagePath("pdf", $user_id);
    if(!file_exists($path)){
      mkdir($path);
      chmod($path, 0755);
    }else{
      array_map('unlink', glob($path . "*.pdf"));
    }

    $namefile = basename($oldfilename, ".pdf");
    $namefile = str_replace(' ', '_', $namefile);
    $namefile = str_replace(',', '', $namefile);
    $namefile = explode(".", $namefile);
    $namefile = $namefile[0];

    $newfilename = $this->imagePath("out", $user_id) . $namefile . '_firmado.pdf';

    $files_jpg = [];
    foreach ($files as $file) {
      $files_jpg[] = $file['filepath'];
    }

    $pdf = new \Imagick($files_jpg);

    $pdf->setImageFormat('pdf');
    $pdf->writeImages($newfilename, true);

    $newfile = '/storage/images/out/' . $user_id . '/' . $namefile . '_firmado.pdf';
    return [
      'success' => true,
      'filepath' => $newfile,
      'filename' => basename($newfile),
    ];
  }

  public function cleanPath($path, $extension)
  {
    try {
      $ext = "*." . $extension;
      if(!file_exists($path)){
        mkdir($path);
        chmod($path, 0755);
      }else{
        array_map('unlink', glob($path . $ext));
      }
      return true;
    } catch (Exception $e) {
      return false;
    }

  }

  public function pngTransparent($user_id, $file_in, $file_out)
  {
    $path = $this->imagePath("transp", $user_id);

    # create new ImageMagick object
    $im = new \Imagick($file_in);
    # remove extra white space
    // $im->clipImage(0);
    $im->setImageFormat('png');

    $color = "rgb(255,255,255)";
    $alpha = 0.0;
    $fuzz = 0;
    $im->transparentPaintImage($color, $alpha, $fuzz * \Imagick::getQuantum(), false);

    $im->despeckleimage();
    header('Content-Type: image/png');

    $response = $im->writeImage($file_out);

    return $response;

  }
}
