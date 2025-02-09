<?php

use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/email', function () {
    
    Mail::raw('Mensagem de teste de RH MANGNT', function (Message $message) {
        $message->to('teste@gmail.com')
                ->subject('Bem-vindo ao RH MANGNT')
                ->from('rh@rhmangnt.com');
    });

    echo 'E-mail enviado com sucesso.';

});

Route::get('/admin', function () {
    $admin = User::with('detail', 'department')->find(1);
    return view('admin', compact('admin'));
});

Route::view('/login', 'auth.login');
