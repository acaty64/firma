<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GuideSignTest extends TestCase
{
    /** @test */
    public function ApiGuideSignTest()
    {
        $local_file = storage_path('app/public/images/test/fake_firma.png');

        $uploadedFile = new UploadedFile(
            $local_file,
            'fake_firma.png',
            'image/png',
            null,
            // null,
            true
        );

        $request = [
                'user_id'       => 'x',
                'file_sign'     => $uploadedFile
            ];
        $response = $this->post('/api/guide/sign', $request);

        $response->assertStatus(200)
                ->assertJsonFragment([
                        'path'  => 'images/view/x/'
                    ]);
        $this->assertTrue(file_exists($response['filepath']));
    }
}
