<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class EmailController extends Controller {
    public function enviarEmailRecuperacaoSenha(Request $request) {
        $credentials = $request->only('email');

        // Esta função envia um e-mail com um link para redefinir a senha
        Password::sendResetLink($credentials);

        return response()->json(['mensagem' => 'E-mail de recuperação de senha enviado com sucesso']);
    }
}
