<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();
        Admins::create(array(
            'a_name'     => 'Chris Sevilleja',
            'a_uname' => 'sevilayha',
            'a_email'    => 'chris@scotch.io',
            'a_password' => Hash::make('awesome'),
            'a_mobile' => '',
            'a_phone' => '',
            'a_address' => '',
            'a_gender' => '',
            'a_birth_date' => '',
            'a_lang' => '',

        ));
    }
}
