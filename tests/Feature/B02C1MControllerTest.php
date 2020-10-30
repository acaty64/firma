<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class B02C1MControllerTest extends TestCase
{
    use Imagenes;
    use RefreshDatabase;

    /** @test */
    public function B02C1MControllerIndexTest()
    {
        // $this->markTestIncomplete('En construccion');

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'jdoe@gmail.com'
        ]);
        $this->actingAs($user);
        $response = $this->get('/c1m');
        $response->assertViewIs('app.c1m.index');

    }

    /** @test */
    public function B02C1MControllerMergeTest()
    {
        // $this->markTestIncomplete('En construccion');

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'jdoe@gmail.com'
        ]);

        $this->actingAs($user);

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
            'user_id' => $user->id,
            'archivo' => $uploadedFront,
        ];

        $response = $this->post('/c1m/merge', $request);
        $response->assertStatus(200)
                ->assertViewIs('app.c1m.download');

    }

}
