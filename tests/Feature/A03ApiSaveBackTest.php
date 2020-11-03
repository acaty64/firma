<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A03ApiSaveBackTest extends TestCase
{
    use DatabaseTransactions;
    /** @test */
    public function ApiSaveBackTest()
    {
        $local_file = storage_path('app/public/images/test/PAPEL_MEMBRETADO_3.pdf');

        $uploadedFile = new UploadedFile(
            $local_file,
            'PAPEL_MEMBRETADO_3.pdf',
            'application/pdf',
            null,
            // null,
            true
        );

        $request = [
                'user_id'       => 'x',
                'file_back'     => $uploadedFile,
            ];
        $response = $this->post('/api/doc/saveBack',$request);

        $response->assertStatus(200)
                ->assertJsonFragment([
                        'num_pages_pdf'  => 3
                    ]);

        $this->assertTrue(file_exists($response['pages'][0]));
    }
}
