<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	if(env('APP_ENV') === 'testing'){
	        $this->call(UserSeeder::class);
    	}
        $this->call(ProfileSeeder::class);
        $this->call(AccessSeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(MenuSeeder::class);
    }
}
