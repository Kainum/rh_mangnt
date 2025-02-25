<?php

namespace App\Services;

class GeneralServices
{
    public static function checkIfSalaryIsGreaterThan($salary, $amount)
    {
        return $salary > $amount;
    }

    public static function createPhraseWithNameAndSalary($name, $salary)
    {
        return "O salário do(a) $name é R\$ $salary";
    }

    public static function getSalaryWithBonus($salary, $bonus)
    {
        return $salary + $bonus;
    }

    public static function fakeDataInJson() {
        // cria 10 clientes com dados falsos
        $clients = [];
        $faker = \Faker\Factory::create();

        for($i = 0; $i < 10; $i++) {
            $clients[] = [
                'name' => $faker->name(),
                'email' => $faker->email(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
            ];
        }

        return json_encode($clients);
    }
}
