<?php

namespace Tests\Feature;

use App\Traits\Imagenes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class A05TraitJpgToOnePdfTest extends TestCase
{
    use Imagenes;

    /** @test */
    public function TraitJpgToOnePdfTest()
    {
        $this->MarkTestIncomplete('NOT USING');
        $user_id = "x";
        if(file_exists(storage_path() . '/app/public/images/back/x/page-2.jpg')){
            copy(storage_path() . '/app/public/images/back/x/page-2.jpg', storage_path() . '/app/public/images/work/x/page-2.jpg');
        }

        $file_in = 'storage/images/work/x/page-2.jpg';

        $response = $this->jpgToOnePdf($file_in, $user_id);

        $this->assertTrue($response == [
                        'success'  => true,
                        'file_out' => storage_path('app/public/images/pdf/x/page-2.pdf')
                    ]);

        $this->assertTrue(file_exists($response['file_out']));
    }
}
