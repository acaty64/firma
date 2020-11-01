<?php

use App\Access;
use Illuminate\Database\Seeder;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
Access::create([ 'id' => '1',  'user_id' => '1',  'profile_id' => '1', ]);
Access::create([ 'id' => '2',  'user_id' => '2',  'profile_id' => '2', ]);
Access::create([ 'id' => '3',  'user_id' => '3',  'profile_id' => '3', ]);

    }
}
