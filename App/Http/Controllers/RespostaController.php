<?php

namespace App\Http\Controllers;

use App\Models\Resposta;
use Illuminate\Http\Request;
use App\Repositories\RespostaRepository;


class RespostaController extends Controller
{

    public function __construct(Resposta $resposta) {
        $this->resposta = $resposta;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $respostaRepository = new RespostaRepository($this->resposta);

        if($request->has('filtro')){
            $respostaRepository->filtro($request->filtro);
        }
        return response()->json($respostaRepository->getResultado(), 200);
    }

  
}
