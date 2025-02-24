<?php

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Session;

beforeEach(function () {
    Session::start();
});


it('display the login page when not logged in', function () {
    // verifica, no contexto do fortify, se, ao entrar na página inicial, vai ser redirecionado à página de login
    $response = $this->get('/')->assertRedirect('/login');

    // verifica se o status é 302
    expect($response->status())->toBe(302);

    // verifica se a rota de login é acessível com status 200
    $response = $this->get('/login');
    expect($response->status())->toBe(200);

    // verificar se a página de login contém o texto "Esqueceu a sua senha?"
    expect($response->content())->toContain("Esqueceu a sua senha?");
});


it('display the recover password page correctly', function () {
    $response = $this->get('/forgot-password');
    expect($response->status())->toBe(200);
    expect($response->content())->toContain("Já sei a minha senha?");
});


it('tests if an admin user can login with success', function () {
    // criar um admin
    addAdminUser();

    // login com o admin criado
    $result = $this->post('/login', [
        '_token' => csrf_token(),
        'email' => 'admin@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    // verifica se o login foi feito com sucesso
    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));
});


it('tests if an rh user can login with success', function () {
    addDepartment();

    // criar um usuário rh
    addRhUser();

    // login com o rh criado
    $result = $this->post(route('login'), [
        '_token' => csrf_token(),
        'email' => 'rh01@rhmangnt.com',
        'password' => 'Aa123456',
    ]);

    // verifica se o login foi feito com sucesso
    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));

    // verifica se o user rh consegue acesso à página exclusiva
    expect($this->get(route('colaborators.create'))->status())->toBe(200);
});


function addAdminUser()
{
    User::insert([
        'name' => 'Administrador',
        'email' => 'admin@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'admin',
        'permissions' => '["admin"]',
        'department_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function addRhUser()
{
    User::insert([
        'name' => 'Colaborador do RH',
        'email' => 'rh01@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'rh',
        'permissions' => '["rh"]',
        'department_id' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function addDepartment() {
    $departments = [
        'Administração',
        'Recursos Humanos',
        'Financeiro',
        'Comercial',
    ];

    foreach($departments as $dep) {
        Department::create([
            'name' => $dep,
        ]);
    }
}
