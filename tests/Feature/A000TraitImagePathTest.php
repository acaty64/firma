<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A000TraitImagePathTest extends TestCase
{
    use Imagenes;

    /** @test */
    public function ImagePathTest()
    {

        $user_id = 'x';

        $path = storage_path('app/public/images/back/') . $user_id . '/';
        $traitPath = $this->imagePath('back', $user_id);

        $this->assertTrue($path == $traitPath);

    }

    /** @test */
    public function CleanPathTest()
    {

        $user_id = 'x';

        $fake_file = $this->imagePath('test') . 'fake_firma.png';

        $newfile = $this->imagePath('back', $user_id) . 'fake_firma.png';

        copy($fake_file, $newfile);

        $path = $this->imagePath('back', $user_id);

        $response = $this->cleanPath($path, 'png');

        $this->assertTrue($response);

        $this->assertTrue(!file_exists($newfile));

    }

    /** @test */
    public function DefaultPathTest()
    {

        $user_id = 'x';

        $path = $this->imagePath('guide');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('test');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('back', $user_id);
        $this->cleanPath($path, 'png');
        $path = $this->imagePath('back');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('original', $user_id);
        $this->cleanPath($path, 'png');
        $path = $this->imagePath('original');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('out', $user_id);
        $this->cleanPath($path, 'png');
        $path = $this->imagePath('out');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('pdf', $user_id);
        $this->cleanPath($path, 'pdf');
        $path = $this->imagePath('pdf');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('png', $user_id);
        $this->cleanPath($path, 'png');
        $path = $this->imagePath('png');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('view', $user_id);
        $this->cleanPath($path, 'png');
        $path = $this->imagePath('view');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('work', $user_id);
        $this->cleanPath($path, 'png');
        $path = $this->imagePath('work');
        $this->assertTrue(file_exists($path . '.gitignore'));

        $path = $this->imagePath('transp', $user_id);
        $this->cleanPath($path, 'png');
        $path = $this->imagePath('transp');
        $this->assertTrue(file_exists($path . '.gitignore'));


    }

}
