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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
           ////pro validade funcionar precisa implementar do lado do cliente: Accept - application/json - sem isso, vai retornar a rota raiz da aplicacao - a pagina laravel
        $request->validate($this->resposta->rules());
        $resposta = $this->resposta->create([
        'pergunta_id' => $request->pergunta_id,
        'resposta' => $request->resposta
        ]);

        return response()->json($resposta, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resposta  $resposta
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $resposta = $this->resposta->find($id);
        if($resposta === null) {
            return response()->json(['erro' => 'Resposta não existe.'], 404);
        }

        return response()->json($resposta, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resposta  $resposta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $resposta = $this->resposta->find($id);
        if($resposta === null) {
            return response()->json(['erro' => 'Resposta não existe.'], 404);
        }
        $request->validate($this->resposta->rules());

        $resposta->update($request->all());
        return response()->json($resposta, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resposta  $resposta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $resposta = $this->resposta->find($id);
        if($resposta === null) {
            return response()->json(['erro' => 'Resposta não existe.'], 404);
        }
        $resposta->delete();
        return ['msg' => 'Resposta removida!'];
    }
}
