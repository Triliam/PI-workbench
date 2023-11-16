<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class FaqsController extends Controller
{

    public function index() {
        $result = DB::table('temas')
        ->join('perguntas', 'temas.id', '=', 'perguntas.tema_id')
        ->join('respostas', 'perguntas.id', '=', 'respostas.pergunta_id')
        ->select('temas.tema', 'temas.icone', 'perguntas.id', 'perguntas.pergunta', 'respostas.resposta')
        ->orderBy('perguntas.id', 'desc')
        ->get();

        return response()->json($result);
    }

    // public function mostrarPerguntasSugeridas() {
    //     $resultado = DB::table('perguntas')
    //     ->select('perguntas.user_id', 'perguntas.pergunta_sugerida')
    //     ->get();

    //     return response()->json($resultado);
    // }
}
