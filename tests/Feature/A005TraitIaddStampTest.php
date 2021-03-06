<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A005TraitIaddStampTest extends TestCase
{
    use Imagenes;
    use DatabaseTransactions;

    /** @test */
    public function A005TraitIaddStampTest()
    {
        $user_id = "x";

        $this->cleanPath('work', $user_id);
        $this->cleanPath('back', $user_id);
        $file_test = $this->imagePath('test') . 'back/page-2.jpg';
        $file_back = $this->imagePath('back', 'x') . 'page-2.jpg';
        copy($file_test, $file_back);

        $old = new \Imagick($file_back);
        $res_old = $old->getImageResolution();

        $file_in = [
            'filepath' => $this->imagePath('back', 'x') . 'page-2.jpg',
            'filename' => 'page-2.jpg',
            'path' => 'images/back/x',
        ];

        $file_sign = [
            'filepath' => $this->imagePath('test') . 'fake_firma.png',
            'filename' => 'fake_firma.png',
            'path' => 'images/test',
        ];
        $seccion = 3;
        $posX = 50;
        $posY = 50;
        $file_out = [
            'filepath' => $this->imagePath('work', 'x') . 'page-2.jpg',
            'filename' => 'page-2.jpg',
            'path' => 'images/work/x',
        ];

        $response = $this->iAddStamp($file_in, $file_sign, $seccion, $posX, $posY, $file_out);

        $this->assertTrue($response == $file_out);

        $this->assertTrue(file_exists($file_out['filepath']));

        $new = new \Imagick($file_out['filepath']);
        $res_new = $new->getImageResolution();

        $this->assertTrue($res_old == $res_new);

    }
}
