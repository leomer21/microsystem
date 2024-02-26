<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        factory('App\Users',667)->create();
        factory('App\Network',5)->create();
        factory('App\AreaGroup',52)->create();
        factory('App\MacAddress',3526)->create();
        factory('App\Email',2135)->create();
        factory('App\Mobile',4023)->create();
        factory('App\MacData',421)->create();
        factory('App\MacAddress',150)->create();
        factory('App\Branch',13)->create();
    }
}
