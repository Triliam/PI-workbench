<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Pergunta;

class AuthController extends Controller
{
    public function loginToken(Request $request) {

        $credenciais = $request->all(['email', 'password']);

        //autenticacao (email e senha)

        $token = auth('api')->attempt($credenciais);

        //usuario autenticado com sucesso
        if($token) {
            return response()->json(['token' => $token]);
            //erro de usuario ou senha
        } else {
            return response()->json(['erro' => 'Usuário ou senha inválidos!'], 403);
        }
        //403 = forbidden -> proibido (login invalido)
    }
//retornar array com perguntas feitas por este user e que o campo atualizacao esta como 0, se tiver como 1 retorna vazio
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Email ou senha inválidos'], 401);
        }

        // Get user details from database
        $user = User::where('email', $request->email)->first();

        // Verificacao se o usuário está bloqueado
        if ($user->bloqueado === 1) {
            return response()->json(['error' => 'Este usuário está bloqueado. Entre em contato com o suporte.'], 401);
        }

        //verificar se o usuario logado é aluno
        if($user->level === 0) {

            $arrayPerguntas =[];
            $perguntas = Pergunta::all();
            // $perguntas = Pergunta::where('user_id', $user->id);

            foreach($perguntas as $p) {
                if($p->pergunta_estado === 1) {
                    if($p->pergunta_atualizacao === 1 && $p->user_id == $user->id){
                        $arrayPerguntas[] = $p;
                    } else {
                        $arrayPerguntas = [];
                    }
                }
            }

            return response()->json([
                'message' => 'Logado com sucesso',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'level' => $user->level
                ],
                'pergunta' => $arrayPerguntas
            ]);
        }

        // Return JSON data for user details
        return response()->json([
            'message' => 'Logado com sucesso',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $user->level]
        ]);
    }

    public function logout() {
        auth('api')->logout(); //cliente encaminhe um jwt valido
        return response()->json(['msg' => 'Logout realizado com sucesso!']);
    }

    public function refresh() {
        $token = auth('api')->refresh(); //cliente encaminhe um jwt valido
        return response()->json(['token' => $token]);
    }

//retornar array com perguntas feitas por este user e que o campo atualizacao esta como 1, se tiver como 0 retorna vazio
    public function me() {

        $user = auth()->user();
        $id = $user->id;

        if($user->level === 0) {

            $arrayPerguntas =[];
            $perguntas = Pergunta::all();

            foreach($perguntas as $p) {
                if($p->pergunta_estado === 1) {
                    if($p->pergunta_atualizacao === 1 && $p->user_id == $id){
                        $arrayPerguntas[] = $p;
                    } else {
                        $arrayPerguntas = [];
                    }
                }
            }
        return response()->json([
            'user' => auth()->user(),
            'perguntas' => $arrayPerguntas]);
        }
        return response()->json(['user' => auth()->user()]);
    }
}


    //gera o token por meio de usuario(no caso email) e senha
    //cliente armazena
    //cliente precisa implementar no headers: Key:Authorization, Value Bearer token gerado
