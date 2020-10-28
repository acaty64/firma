<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A004TraitJpg2Png_tTest extends TestCase
{
    use Imagenes;

    /** @test */
    public function A004TraitJpg2PngTest()
    {

        $user_id = "x";
        $file = "/home/vagrant/code/firma/public/storage/images/test/fake_photo.jpg";

        $response = $this->jpg2Png($user_id, $file);

        $this->assertTrue($response == $this->imagePath('png', 'x') . 'fake_photo.png');

        $this->assertTrue(file_exists($response));

    }

    /** @test */
    public function A004TraitPngResizeTest()
    {

        $user_id = "x";

        $file = $this->imagePath('test') . "fake_photo.jpg";

        $imagick = new \Imagick($file);
        $old_size = [$imagick->getImageWidth() , $imagick->getImageHeight()];

        $new_file = $this->imagePath('png', $user_id) . "fake_photo.png";

        $response = $this->resizeImagick($file, 0.50, 'x', $new_file);

        $imagick = new \Imagick($response);
        $new_size = [$imagick->getImageWidth() , $imagick->getImageHeight()];

        $this->assertTrue($old_size[0] == $new_size[0]*2);
        $this->assertTrue($old_size[1] == $new_size[1]*2);

    }


    /** @test */
    public function A004TraitPngToPngTest()
    {

        $user_id = "x";

        $file_in = "/home/vagrant/code/firma/public/storage/images/png/x/fake_photo.png";
        $file_out = "/home/vagrant/code/firma/public/storage/images/transp/x/fake_photo.png";


        $response = $this->pngTransparent($user_id, $file_in, $file_out);

        $this->assertTrue($response == true);

        $this->assertTrue(file_exists($file_out));

        // $this->MarkTestIncomplete('ToDo: check image transparent: ' . $file_out);
    }


}

