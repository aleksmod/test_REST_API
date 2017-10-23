<?php

require 'vendor/autoload.php';

$faker = Faker\Factory::create();

$repository = [];
for ($i = 0; $i <= 20; $i++) {
    $id = $faker->unique()->numberBetween(1, 100);
    $name = $faker->name;
    $postcode = $faker->postcode;
    $state = $faker->state;
    $city = $faker->city;
    $streetName = $faker->streetName;
    $buildingNumber = $faker->buildingNumber;
    $phone = $faker->e164PhoneNumber;
    $email = $faker->email;

    $data = compact(['id', 'name', 'postcode', 'state', 'streetName', 'buildingNumber', 'phone', 'email']);
    $repository[] = $data;
}

file_put_contents('repository.php', var_export($repository, true));
