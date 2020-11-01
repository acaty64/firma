<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([ 'id' => '1',  'name' => 'ana',  'email' => 'aarashiro@ucss.edu.pe',  'password' => bcrypt('secret'), ]);
		User::create([ 'id' => '2',  'name' => 'john',  'email' => 'jdoe@ucss.edu.pe',  'password' => bcrypt('secret'), ]);
		User::create([ 'id' => '3',  'name' => 'jane',  'email' => 'janedoe@ucss.edu.pe',  'password' => bcrypt('secret'), ]);

    }
}
