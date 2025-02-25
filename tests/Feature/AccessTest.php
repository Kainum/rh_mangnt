<?php


it('tests if an admin user can see the RH users page', function () {
    // criar o admin
    addAdminUser();

    // efetuar o login com o admin
    auth()->loginUsingId(1);

    // verifica se acessa com sucesso a página de RH users
    expect($this->get('/colaborators/rh')->status())->toBe(200);
});


it('tests if is not possible to access the home page without logged user', function () {
    // verifica se é possível entrar na home page
    $result = $this->get('/home');
    expect($result->status())->toBe(302);
    $result->assertRedirect('/login');
    
    // ou
    expect($result->status())->not()->toBe(200);
});


it('tests if logged in user can access the login page', function () {
    // adicionar admin na base de dados
    addAdminUser();

    // verifica se está logado
    auth()->loginUsingId(1);

    expect($this->get('/login')->status())->not()->toBe(200);
});


it('tests if logged in user can access the recover password page', function () {
    // adicionar admin na base de dados
    addAdminUser();

    // verifica se está logado
    auth()->loginUsingId(1);

    expect($this->get('/forgot-password')->status())->not()->toBe(200);
});