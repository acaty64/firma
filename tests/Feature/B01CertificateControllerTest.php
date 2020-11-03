<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class B01CertificateControllerTest extends TestCase
{
    use Imagenes;
    use DatabaseTransactions;

    /** @test */
    public function B01CertificateControllerIndexTest()
    {
        // $this->markTestIncomplete('En construccion');

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'jdoe@gmail.com'
        ]);
        $this->actingAs($user);
        $response = $this->get('/certificate');
        $response->assertViewIs('app.certificates.index');

    }

    /** @test */
    public function B01CertificateControllerMergeTest()
    {
        // $this->markTestIncomplete('En construccion');

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'jdoe@gmail.com'
        ]);

        $this->actingAs($user);

        $fake_front = storage_path('app/public/images/test/fake_CE.pdf');

        $uploadedFront = new UploadedFile(
            $fake_front,
            'fake_CE.pdf',
            'application/pdf',
            null,
            // null,
            true
        );

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
            'user_id' => $user->id,
            'archivo' => $uploadedFront,
            'photo' => $uploadedPhoto
        ];

        $response = $this->post('/certificate/merge', $request);
        $response->assertStatus(200)
                ->assertViewIs('app.certificates.download');

    }

}
