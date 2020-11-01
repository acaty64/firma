<?php

use App\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
Menu::create([ 'id' => '1',  'profile_id' => '1',  'option_id' => '1', ]);
Menu::create([ 'id' => '2',  'profile_id' => '1',  'option_id' => '2', ]);
Menu::create([ 'id' => '3',  'profile_id' => '1',  'option_id' => '3', ]);
Menu::create([ 'id' => '4',  'profile_id' => '1',  'option_id' => '4', ]);
Menu::create([ 'id' => '5',  'profile_id' => '1',  'option_id' => '5', ]);
Menu::create([ 'id' => '6',  'profile_id' => '1',  'option_id' => '6', ]);
Menu::create([ 'id' => '7',  'profile_id' => '1',  'option_id' => '7', ]);
Menu::create([ 'id' => '8',  'profile_id' => '2',  'option_id' => '1', ]);
Menu::create([ 'id' => '9',  'profile_id' => '2',  'option_id' => '4', ]);
Menu::create([ 'id' => '10',  'profile_id' => '3',  'option_id' => '1', ]);
Menu::create([ 'id' => '11',  'profile_id' => '3',  'option_id' => '2', ]);
Menu::create([ 'id' => '12',  'profile_id' => '3',  'option_id' => '3', ]);
Menu::create([ 'id' => '13',  'profile_id' => '3',  'option_id' => '4', ]);

    }
}
