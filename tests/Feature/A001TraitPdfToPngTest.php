<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A001TraitPdf2PngTest extends TestCase
{
    use Imagenes;

    /** @test */
    public function A001TraitPdf2PngTest()
    {

        // $this->MarkTestIncomplete('test only file, not image');
        $local_file = storage_path('app/public/images/test/PAPEL_MEMBRETADO_3.pdf');

        $uploadedFile = new UploadedFile(
            $local_file,
            'PAPEL_MEMBRETADO_3.pdf',
            'application/pdf',
            null,
            // null,
            true
        );

        $user_id = 'x';
        $response = $this->pdf2png($user_id, $uploadedFile);

        $check = [
            'success' => true,
            'filepath' => [
                ''
            ]
        ];

        $new_file = $this->imagePath('png', $user_id) . 'page-0.png';
        $this->assertTrue(file_exists($new_file));

        $new_file = $this->imagePath('png', $user_id) . 'page-1.png';
        $this->assertTrue(file_exists($new_file));

        $new_file = $this->imagePath('png', $user_id) . 'page-2.png';
        $this->assertTrue(file_exists($new_file));

    }
}
