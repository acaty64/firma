<?php

namespace Tests\Unit;

use App\Access;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class A001AccessTest extends TestCase
{
	use DatabaseTransactions;

    /** @test **/
    public function a_non_user_redirect_to_login()
    {
        $this->get('/home')
            ->assertRedirect('login');
    }

    /** @test **/
    public function an_authorized_user_redirect_to_home()
    {
        $user = User::findOrFail(3);

        $req = $this->actingAs($user)
            ->get('/home')
            ->assertViewIs('home');
    }

    /** @test **/
    public function an_admin_user_view_index()
    {
        $user = User::findOrFail(1);
        $this->actingAs($user)
        	->get(route('access.index'))
        	->assertStatus(200);
    }

    /** @test **/
    public function a_non_admin_user_cannot_view_index()
    {
        $user = User::find(2);
        $this->actingAs($user)
        	->get(route('access.index'))
        	->assertStatus(403)
        	->assertViewIs('errors.forbidden');
    }

    /** @test **/
    public function an_admin_user_can_store_a_new_access()
    {
        $user = User::find(1);
    	$request = ['newuser'=>5];
        $response = $this->actingAs($user)
        	->post(route('access.store'), $request)
    		->assertStatus(302)
    		->assertRedirect(route('access.index'));

    	$this->assertDatabaseHas('accesses', ['user_id' => 5]);
    }

    /** @test **/
    public function it_can_destroy_an_access()
    {
        $user = User::find(1);
    	$request = ['newuser'=>5];
        $access = Access::create([
    		'user_id' => 5,
            'profile_id' => 2
    	]);
        $response = $this->actingAs($user)
        	->get(route('access.destroy', $access->id))
    		->assertStatus(302)
    		->assertRedirect(route('access.index'));

    	$this->assertDatabaseMissing('accesses', ['user_id' => $access->id]);
    }

}
