<?php

use App\Models\User;


it('tests if an admin can insert a new rh user', function () {
    // criar user admin
    addAdminUser();

    // criar departamentos
    addDepartments();

    // fazer login com o admin
    $result = $this->post(route('login.store'), [
        'email' => 'admin@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    // verifica se o login foi feito com sucesso
    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));

    // verifica se o admin consegue adicionar user do rh
    $result = $this->post(route('colaborators.rh.store'), [
        'name' => 'RH user 1',
        'email' => 'rhuser@gmail.com',
        'department' => Crypt::encrypt('2'),
        'address' => 'Rua 1',
        'zip_code' => '1234-123',
        'city' => 'Cidade do RH',
        'phone' => '123456789',
        'salary' => '1000.00',
        'admission_date' => '2021-01-12',
    ]);

    // verifica se o user do rh foi inserido com sucesso
    $this->assertDatabaseHas('users', [
        'name' => 'RH user 1',
        'email' => 'rhuser@gmail.com',
        'role' => 'rh',
        'permissions' => '["rh"]',
    ]);
});

it('tests if an rh user can insert a new colaborator', function () {
    // criar user rh user
    addRhUser();

    // criar departamentos
    addDepartments();

    // fazer login com o rh user
    $result = $this->post(route('login.store'), [
        'email' => 'rh01@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    // verifica se o login foi feito com sucesso
    expect(auth()->user()->role)->toBe('rh');

    // verifica se o rh user consegue adicionar user colaborator
    $result = $this->post(route('colaborators.store'), [
        'name' => 'User normal 2',
        'email' => 'user02colab@gmail.com',
        'department' => Crypt::encrypt('3'),
        'address' => 'Rua do colaborador',
        'zip_code' => '5678-324',
        'city' => 'Cidade do Colaborador',
        'phone' => '9876543210',
        'salary' => '345.87',
        'admission_date' => '2023-02-11',
    ]);

    // verifica se o user colaborator foi inserido com sucesso
    // $this->assertDatabaseHas('users', [
    //     'email' => 'user02colab@gmail.com',
    // ]);
    expect(User::where('email', 'user02colab@gmail.com')->exists())->toBeTrue();
});
