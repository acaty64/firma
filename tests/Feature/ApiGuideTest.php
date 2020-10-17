<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiGuideTest extends TestCase
{
    /** @test */
    public function ApiGuideTest()
    {
        $request = [
                'filename'      => 'guide.jpg',
                'filefirma'     => 'sign_guide.png',
                'fileout'       => 'page.jpg',
                'seccion'       => 9,
                'horizontal'    => 50,
                'vertical'      => 50,
                'user_id'       => 'x'
            ];
        $response = $this->post('/api/guide', $request);
        $response->assertStatus(200)
                ->assertJsonFragment([
                        'success' => true,
                        'filebase' => [
                                'filename' => 'page.jpg',
                                'filepath' => public_path('storage/images/view/x/page.jpg'),
                                'path'  => 'images/view/x/'
                        ]]);
    }
}
