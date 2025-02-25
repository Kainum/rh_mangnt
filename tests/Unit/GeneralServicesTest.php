<?php

use App\Services\GeneralServices;

it('tests if the salary is greater than a specific amount', function () {
    $salary = 1000;
    $amount = 500;

    $result = GeneralServices::checkIfSalaryIsGreaterThan($salary, $amount);

    expect($result)->toBeTrue();
});

it('tests if the salary is not greater than a specific amount', function () {
    $salary = 1000;
    $amount = 1500;

    $result = GeneralServices::checkIfSalaryIsGreaterThan($salary, $amount);

    expect($result)->toBeFalse();
});

it('tests if the phrase is created correctly', function () {
    $name = 'Luciano';
    $salary = 1500;

    $result = GeneralServices::createPhraseWithNameAndSalary($name, $salary);

    expect($result)->toBe('O salário do(a) Luciano é R$ 1500');
});

it('tests if the salary with bonus is calculated correctly', function () {
    $salary = 1000;
    $bonus = 25;

    $result = GeneralServices::getSalaryWithBonus($salary, $bonus);

    expect($result)->toBe(1025);
});

it('tests if the fake data is created correctly', function () {
    $result = GeneralServices::fakeDataInJson();

    $clients = json_decode($result, true);

    expect(count($clients))->toBeGreaterThanOrEqual(1);
    expect($clients[0])->toHaveKeys(['name', 'email', 'phone', 'address']);
});
