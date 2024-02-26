<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(App\Users::class, function (Faker\Generator $faker) {
    return [
        'u_name' => $faker->name,
        'u_uname'=>$faker->username,
        'u_phone' => $faker->phoneNumber,
        'u_address'=>$faker->address,
        'u_password' => $faker->regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}'),
        'credit' => $faker->numberBetween(10000,99999),
        'u_canuse'=>$faker->numberBetween(0,1),
        'suspend'=>$faker->numberBetween(0,1),
        'u_state'=>$faker->numberBetween(0,1),
        'u_gender'=>$faker->boolean,
        'note'=>$faker->text(200),
        'u_area'=>$faker->numberBetween(1,20),
        'branch_id'=>$faker->numberBetween(1,55),
        'u_card_date_of_charging'=>$faker->dateTimeBetween($startDate = '-10 months', $endDate = 'now')
    ];
});

$factory->define(App\Network::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'open_system' => $faker->boolean
    ];
});
$factory->define(App\AreaGroup::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'is_active'=>$faker->boolean
    ];
});
$factory->define(App\MacAddress::class, function (Faker\Generator $faker) {
    return [
        'mac' => $faker->macAddress,
        'type'=>$faker->boolean,
        'u_id' => $faker->numberBetween(1,900)
    ];
});
$factory->define(App\Email::class, function (Faker\Generator $faker) {
    return [
        'value' => $faker->email,
        'user_id' => $faker->numberBetween(1,990)
    ];
});
$factory->define(App\Mobile::class, function (Faker\Generator $faker) {
    return [
        'value' => $faker->phoneNumber,
        'user_id' => $faker->numberBetween(1,990)
    ];
});
$factory->define(App\MacData::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->phoneNumber,
        'name' => $faker->word,
        'id_console_google' => $faker->numberBetween(1,990),
        'mac_address_id' => $faker->numberBetween(1,1602)
    ];
});
$factory->define(App\Branch::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'network_id' => $faker->numberBetween(1,1)
    ];
});