<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A04ApiGetPreviewTest extends TestCase
{
    use Imagenes;
    use DatabaseTransactions;
    /** @test */
    public function A04ApiGetPreviewTest()
    {
        // $this->markTestIncomplete('Revisar Snappy');

        $fake_png = storage_path() . '/app/public/images/test/fake_firma.png';
        $fake_original = storage_path() . '/app/public/images/original/x/fake_firma.png';
        $fake_work = storage_path() . '/app/public/images/work/x/fake_firma.png';
        $fake_view = storage_path() . '/app/public/images/view/x/fake_firma.png';
        copy($fake_png, $fake_original);
        copy($fake_png, $fake_work);
        copy($fake_png, $fake_view);

        $request = [
            "user_id" => "x",
            "fileback" => [
                "filename" => "PAPEL_MEMBRETADO_3.pdf",
                "pages" => [
                        $this->imagePath("back", "x") . "page-0.jpg",
                        $this->imagePath("back", "x") . "page-1.jpg",
                        $this->imagePath("back", "x") . "page-2.jpg",
                    ]
            ],
            "filefirma"  => [
                "path" => "images/view/",
                "filename" => "fake_firma.png",
                "filepath" => $this->imagePath("view", "x") . "fake_firma.png"
            ],
            "seccion"  => "3" ,
            "horizontal"  => "50" ,
            "vertical"  => "20" ,
            "hojas" => "ultima",
            "range_page"  => "",
            "porc_sign" => "50"
        ];

        $response = $this->post('/api/doc/preview',$request);

        $response->assertStatus(200)
                ->assertJsonFragment([
                        'filepath'  => '/storage/images/out/x/PAPEL_MEMBRETADO_3[S].pdf'
                    ]);
        $newFile = storage_path() . '/app/public/images/out/x/' . $response['filename'];

        $this->assertTrue(file_exists($newFile));

    }
}
