<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use Illuminate\Http\Request;
use App\Repositories\TemaRepository;

class TemaController extends Controller
{

    public function __construct(Tema $tema) {
        $this->tema = $tema;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $temaRepository = new TemaRepository($this->tema);

        if($request->has('filtro')){
            $temaRepository->filtro($request->filtro);
        }
        return response()->json($temaRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //pro validade funcionar precisa implementar do lado do cliente: Accept - application/json - sem isso, vai retornar a rota raiz da aplicacao - a pagina do laravel
       $request->validate($this->tema->rules(), $this->tema->feedback());
        $tema = $this->tema->create([
            'user_id' => $request->user_id,
            'tema' => $request->tema,
            'icone' => $request->icone
        ]);

        return response()->json($tema, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $tema = $this->tema->find($id);
        if($tema === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe.'], 404);
        }
        return response()->json($tema, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //tema->update($request->all());

        $tema = $this->tema->find($id);
        if($tema === null) {
            return response()->json(['erro' => 'Não é possivel atualizar, não encontrado.'], 404);
        }
        if($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            //percorrer todas as regras definidas no Model
            foreach($tema->rules() as $input => $regra) {
                //coletar apenas as regras aplicaveis aos paramentros parciais da requisicao (só o que quer atualizar)
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $tema->feedback());
        } else {
            $request->validate($this->tema->rules(), $this->tema->feedback());

        }
        $tema->update($request->all());
        return response()->json($tema, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $tema = $this->tema->find($id);
        if($tema === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe.'], 404);
        }
        $tema->delete();
        return ['msg' => 'Tema removido!'];
    }
//deletar em cascata desativar em cascata soft-delete
}
