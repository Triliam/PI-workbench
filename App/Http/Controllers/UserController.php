<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index() {

        return response()->json($this->user->all(), 200);
    }

    public function store(Request $request) {


        $request->validate($this->user->rules(), $this->user->feedback());
        $user = User::create([
            'name'=>$request->input('name'),
            'level'=>$request->input('level'),
            'email'=>$request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return $user;
    }

    public function show($id) {
        $user = $this->user->find($id);
        if($user === null) {
            return response()->json(['erro' => 'Usuario não encontrado.'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request, $id) {

        $user = $this->user->find($id);
        if($user === null) {
            return response()->json(['erro' => 'não foi possivel atualizar, usuário não encontrado.'], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();
        //$user->update($request->all());
        return response()->json($user, 200);
        }

    public function destroy(User $user) {
        $user->delete();
        return response()->json(['sucess'=>true]);
    }

    public function getUsersWithLevelOne() {
        $users = User::where('level', 1)->get();
        return response()->json($users);
    }

    public function blockUser($id)
    {
        // Encontrar o usuário pelo ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Atualizar o campo 'bloqueado' para true (ou 1, dependendo do tipo de campo)
        $user->bloqueado = 1;
        $user->save();

        return response()->json(['message' => 'Usuário bloqueado com sucesso']);
    }
}


