<?php

namespace Tests\Unit;

use App\Access;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AccessTest extends TestCase
{
	use DatabaseTransactions;

    /** @test **/
    public function a_master_user_view_index()
    {
        $user = User::create([
    		'name' => 'John Doe',
    		'email' => 'jdoe@gmail.com'
    	]);
        $this->actingAs($user)
        	->get(route('access.index'))
        	->assertStatus(200);
    }

    /** @test **/
    public function a_non_master_user_cannot_view_index()
    {
        $user1 = User::create([
    		'name' => 'John Doe',
    		'email' => 'jdoe@gmail.com'
    	]);
        $user2 = User::create([
    		'name' => 'Jane Doe',
    		'email' => 'janed@gmail.com'
    	]);
        $this->actingAs($user2)
        	->get(route('access.index'))
        	->assertStatus(200)
        	->assertViewIs('app.unlogued');
    }

    /** @test **/
    public function it_can_store_a_new_access()
    {
    	$request = ['newuser'=>5];
        $user = User::create([
    		'name' => 'John Doe',
    		'email' => 'jdoe@gmail.com'
    	]);
        $response = $this->actingAs($user)
        	->post(route('access.store'), $request)
    		->assertStatus(302)
    		->assertRedirect(route('access.index'));

    	$this->assertDatabaseHas('accesses', ['user_id' => 5]);
    }

    /** @test **/
    public function it_can_destroy_an_access()
    {
    	$request = ['newuser'=>5];
        $user = User::create([
    		'name' => 'John Doe',
    		'email' => 'jdoe@gmail.com'
    	]);
        $access = Access::create([
    		'user_id' => 5,
    	]);

        $response = $this->actingAs($user)
        	->get('/access/destroy/' . $access->id)
    		->assertStatus(302)
    		->assertRedirect(route('access.index'));

    	$this->assertDatabaseMissing('accesses', ['user_id' => $access->id]);
    }

}
