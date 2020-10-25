<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A04TraitJpgToPdfTest extends TestCase
{
    use Imagenes;

    /** @test */
    public function A04TraitJpgToPdfTest()
    {
        // $this->MarkTestIncomplete('test only file, not image');

        $user_id = "x";
        $files = [
            ["filepath" => "/home/vagrant/code/firma/public/storage/images/work/x/page-0.jpg"],
            ["filepath" => "/home/vagrant/code/firma/public/storage/images/work/x/page-1.jpg"],
            ["filepath" => "/home/vagrant/code/firma/public/storage/images/work/x/page-2.jpg"]
            ];

        $check = [];

        foreach ($files as $file) {

            copy($this->imagePath('test') . 'work/' . basename($file['filepath']), $file['filepath']);
        }

        $oldfilename = "test_prueba.pdf";

        $response = $this->jpgToPdf($files, $oldfilename, $user_id);

        $this->assertTrue($response == [
                        'success'  => true,
                        'filepath' => '/storage/images/out/x/test_prueba_firmado.pdf',
                        'filename' => 'test_prueba_firmado.pdf'
                    ]);

        $newFile = $this->imagePath('out', 'x') . '/test_prueba_firmado.pdf';
        $this->assertTrue(file_exists($newFile));

        $this->MarkTestIncomplete('test only file, check image ' . $newFile);

    }
}
