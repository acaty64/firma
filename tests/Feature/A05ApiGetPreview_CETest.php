<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A05ApiGetPreview_CETest extends TestCase
{
    use Imagenes;
    use DatabaseTransactions;
    /** @test */
    public function A05ApiGetPreview_CE_UploadPDFTest()
    {
        // $this->markTestIncomplete('En construccion');

        $user_id = 'x';

        $fake_front = storage_path('app/public/images/test/fake_CE.pdf');

        $uploadedFront = new UploadedFile(
            $fake_front,
            'fake_CE.pdf',
            'application/pdf',
            null,
            // null,
            true
        );

        $request = [
            'user_id' => $user_id,
            'file_PDF' => $uploadedFront
        ];

        $response = $this->post('/api/ce/uploadPDF', $request);

        $this->assertTrue($response['success']);

        foreach ($response['pages'] as $new_file) {
            $file = $this->imagePath('png', $user_id) . basename($new_file);
            $file_out = $this->imagePath('transp', $user_id) . basename($new_file);
            $response2 = $this->pngTransparent($user_id, $file, $file_out);
            $this->assertTrue($response2);
            $this->assertTrue(file_exists($file_out));
        }

    }

    /** @test */
    public function A05ApiGetPreview_CE_UploadPhotoTest()
    {
        // $this->markTestIncomplete('En construccion');

        $user_id = 'x';

        $fake_front = storage_path('app/public/images/test/fake_photo.jpg');

        $uploadedPhoto = new UploadedFile(
            $fake_front,
            'fake_photo.jpg',
            'image/jpg',
            null,
            // null,
            true
        );

        $request = [
            'user_id' => $user_id,
            'file_photo' => $uploadedPhoto
        ];

        $response = $this->post('/api/ce/uploadPhoto', $request);

        $this->assertTrue(file_exists($response['filepath']));

        $imagick = new \Imagick($response['filepath']);

        // $this->assertTrue($imagick->getImageWidth() == 180);
        // $this->assertTrue($imagick->getImageHeight() == 216);

    }

    /** @test */
    public function A05ApiGetPreview_CE_PreviewTest()
    {

        $user_id = 'x';
        $request =[
                'user_id' => $user_id,
                'pages' => [
                        "/home/vagrant/code/firma/storage/app/public/images/transp/x/page-0.png",
                        "/home/vagrant/code/firma/storage/app/public/images/transp/x/page-1.png",
                        "/home/vagrant/code/firma/storage/app/public/images/transp/x/page-2.png"
                    ],
                'file_photo' => "/home/vagrant/code/firma/storage/app/public/images/png/x/photo.png",
                'file_out' => [
                    'filename' => 'CE_20010001.pdf',
                    'imagefile' => '/images/out/x/CE_20010001.pdf'
                ],
            ];

        $response = $this->post('/api/ce/preview', $request)
            ->assertStatus(200);

        $file_out = $this->imagePath('out', $user_id) .
            'CE_20010001[M].pdf';
        $this->assertTrue(file_exists($file_out));

    }
}
