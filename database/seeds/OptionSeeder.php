<?php

use App\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
Option::create([ 'id' => '1',  'name' => 'Firma',  'order' => '1',  'redirect' => 'firma.index', ]);
Option::create([ 'id' => '2',  'name' => 'Const.Estudios',  'order' => '2',  'redirect' => 'certificate.index', ]);
Option::create([ 'id' => '3',  'name' => 'Const.1era.Mat.',  'order' => '3',  'redirect' => 'c1m.index', ]);
Option::create([ 'id' => '4',  'name' => 'Tools',  'order' => '9',  'redirect' => 'tool.index', ]);
Option::create([ 'id' => '5',  'name' => 'Accesos',  'order' => '10',  'redirect' => 'access.index', ]);
Option::create([ 'id' => '6',  'name' => 'Menus',  'order' => '11',  'redirect' => 'menu.index', ]);
Option::create([ 'id' => '7',  'name' => 'Perfiles',  'order' => '12',  'redirect' => 'profile.index', ]);

    }
}
