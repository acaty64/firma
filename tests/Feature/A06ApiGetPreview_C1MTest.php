<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A06ApiGetPreview_C1MTest extends TestCase
{
    use Imagenes;
    use DatabaseTransactions;

    /** @test */
    public function A06ApiGetPreview_C1M_UploadPDFTest()
    {
        // $this->markTestIncomplete('En construccion');

        $user_id = 'x';

        $fake_front = storage_path('app/public/images/test/fake_c1m.pdf');

        $uploadedFront = new UploadedFile(
            $fake_front,
            'fake_c1m.pdf',
            'application/pdf',
            null,
            // null,
            true
        );

        $request = [
            'user_id' => $user_id,
            'file_PDF' => $uploadedFront
        ];

        $response = $this->post('/api/c1m/uploadPDF', $request);

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
    public function A06ApiGetPreview_C1M_PreviewTest()
    {
        $user_id = 'x';
        $request =[
                'user_id' => $user_id,
                'pages' => [
                        $this->imagePath("transp", "x") . "page.png",
                    ],
                'file_out' => [
                    'filename' => 'C1M_202010005.pdf',
                    'imagefile' => '/images/out/x/C1M_202010005.pdf'
                ],
            ];

        $response = $this->post('/api/c1m/preview', $request)
            ->assertStatus(200);

        $file_out = $this->imagePath('out', $user_id) . 'C1M_202010005[M].pdf';
        $this->assertTrue(file_exists($file_out));

    }
}
