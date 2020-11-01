<?php

use App\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
Profile::create([ 'id' => '1',  'name' => 'admin',  'routes' => 'admin', ]);
Profile::create([ 'id' => '2',  'name' => 'user1',  'routes' => 'auth', ]);
Profile::create([ 'id' => '3',  'name' => 'user2',  'routes' => 'auth', ]);

    }
}
