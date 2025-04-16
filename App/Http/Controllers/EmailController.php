<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class EmailController extends Controller {
    
    public function enviarNovaSenha(Request $request) {
        $usuario = User::where('email', $request->email)->first();

        if (!$usuario) {
            return response()->json(['error' => 'E-mail não encontrado'], 404);
        }

        // Gerar uma nova senha temporária
        $novaSenha = Str::random(8); // Gera uma senha de 8 caracteres aleatórios

        // Atualizar a senha do usuário no banco de dados
        $usuario->password = Hash::make($novaSenha);
        $usuario->save();

        // Enviar e-mail com a nova senha
        Mail::send('emails.novaSenha', ['senha' => $novaSenha], function ($message) use ($usuario) {
            $message->to($usuario->email)->subject('Sua nova senha temporária');
        });

        return response()->json(['message' => 'Nova senha enviada com sucesso']);
    }
}
