<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class A000LoginTest extends TestCase
{
	use DatabaseTransactions;

    /** @test */
    public function it_does_not_allow_guests_to_discover_auths_urls()
    {
        $this->get('invalid-url')
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /** @test */
    public function it_displays_404s_when_auths_visit_invalid_url()
    {
    	$user = User::create([
    		'name' => 'John Doe',
    		'email' => 'jdoe@gmail.com'
    	]);
        $this->actingAs($user);

        $response = $this->get('invalid-url');
        $response->assertStatus(404);
    }

    /** @test */
    public function a_logued_user_dont_login_again()
    {
    	$user = User::create([
    		'name' => 'John Doe',
    		'email' => 'jdoe@gmail.com'
    	]);
        $this->actingAs($user);
        $response = $this->get('login')
        			->assertStatus(302)
        			->assertRedirect();
    }

    /** @test */
    public function a_logued_user_view_sign_view()
    {
    	$user = User::create([
    		'name' => 'John Doe',
    		'email' => 'jdoe@gmail.com'
    	]);
        $this->actingAs($user);
        $response = $this->get('sign')
        			->assertStatus(200)
        			->assertViewIs('app.sign');
    }





}
