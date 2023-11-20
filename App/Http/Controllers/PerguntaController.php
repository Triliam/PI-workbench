<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Tema;
use App\Models\Icone;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\PerguntaRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Resposta;

class PerguntaController extends Controller
{

    public function __construct(Pergunta $pergunta) {
        $this->pergunta = $pergunta;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $perguntaRepository = new PerguntaRepository($this->pergunta);

        if($request->has('filtro')){
            $perguntaRepository->filtro($request->filtro);
        }
        return response()->json($perguntaRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $request->validate($this->pergunta->rules(), $this->pergunta->feedback());
        $pergunta = $this->pergunta->create([
            'tema_id' => $request->tema_id,
            'user_id' => $request->user_id,
            'pergunta' => $request->pergunta
        ]);
        return response()->json($pergunta, 201);
    }

    public function storeAluno(Request $request) {

        $request->validate($this->pergunta->rules(), $this->pergunta->feedback());

        if(User::where('level', 0)) {
            $pergunta = $this->pergunta->create([
                'user_id' => $request->user_id,
                'pergunta' => $request->pergunta
            ]);

            //pergunta_sugerida = 1 para notificar ao adm que tem pergunta de aluno para responder
            $pergunta->pergunta_sugerida = 1;

            //pergunta_estado = 0 pois essa pergunta fica offline ate o adm mudar o estado para online e exibi-la
            $pergunta->pergunta_estado = 0;

            //aluno acabou de criar uma pergunta, atualizacao = 0 pois ele ainda nao visualizou sua resposta
            $pergunta->pergunta_atualizacao = 0;
            $pergunta->save();

            $resposta = $request->input('resposta', 'Responda aqui a pergunta do aluno!');

            Resposta::create([
                'pergunta_id' => $pergunta->id,
                'resposta' => $resposta
            ]);
            return response()->json($pergunta, 201);
        }
    }
    public function perguntaAtualizacao($id) {
        //$pergunta = $this->pergunta->find($id);
        $result = Pergunta::where('id', $id)->first();
        if($result->pergunta_atualizacao == 0){
            return response()->json(['msg'=>'Pergunta sem atualização', 'perguntaAtualizacao' => $result->pergunta_atualizacao]);
        }
        return response()->json(['msg'=>'Pergunta com atualização', 'perguntaAtualizacao' => $result->pergunta_atualizacao]);
    }

    //rota para o front de pergunta nao visualizada
    public function perguntaAtualizacao0($id) {
        $pergunta = $this->pergunta->find($id);
        $pergunta->pergunta_atualizacao = 0;
        $pergunta->save();

        return response()->json('Pergunta sem atualização');
    }

     //rota para o front de pergunta visualizada
    public function perguntaAtualizacao1($id) {
        $pergunta = $this->pergunta->find($id);
        $pergunta->pergunta_atualizacao = 1;
        $pergunta->save();
        return response()->json('Pergunta com atualização');
    }

    public function storeTogether(Request $request) {

        //pergunta_sugerida recebe valor 0 pois eh uma pergunta criada pelo adm/colaborador e nao por aluno
        $pergSugerida = 0;

        //pergunta_atualizacao vai receber valor 0 default
        //se pergunta_atualizacao receber valor 0 vai retornar na perguntaAtualizacao0()
        //se pergunta_atualizacao recebe valor 1 retorna na funcao perguntaAtualizacao1()
        //null?
        $pergAtualizacao = 0;

        $pergunta = Pergunta::create([
            'tema_id' => $request->tema_id,
            'user_id' => $request->user_id,
            'pergunta' => $request->pergunta,
            'pergunta_sugerida' => $pergSugerida,
            'pergunta_atualizacao' => $pergAtualizacao,

            //adm/cola escolhe se a pergunta permanece offline(0) ou vai pra exibicao online(1)
            'pergunta_estado' => $request->pergunta_estado
        ]);

        Resposta::create([
            'pergunta_id' => $pergunta->id,
            'resposta' => $request->resposta
        ]);
        return response()->json('Pergunta e resposta cadastradas com sucesso!', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pergunta  $pergunta
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'n existe'], 404);
        }
        return response()->json($pergunta, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pergunta  $pergunta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'Pergunta não existe.'], 404);
        }

        $request->validate($this->pergunta->rules(), $this->pergunta->feedback());

        $pergunta->update($request->all());
        return response()->json($pergunta, 200);
    }

    public function updateTogether(Request $request, $id) {

        $pergunta = $this->pergunta->find($id);
        $pergunta->pergunta = $request->input('pergunta');
        $pergunta->tema_id = $request->input('tema_id');

        //adm/colaborador define se a pergunta permanece offline(0) ou se vai pra exibicao online(1)
        $pergunta->pergunta_estado = $request->input('pergunta_estado');

        //pergunta_sugerida recebe valor 0 pois a pergunta do aluno esta sendo respondida e atulizacao recebe valor 1 pois ha pergunta atualizada, se o user_id dessa pergunta for de algum aluno, ele sera notificado da atualizacao no momento do login contanto que a pergunta esteja online

        $pergunta->pergunta_sugerida = 0;
        $pergunta->pergunta_atualizacao = 1;

        $pergunta->save();

        $resposta = Resposta::where('pergunta_id', $id)->first();
        $resposta->resposta = $request->input('resposta');
        $resposta->save();

        return response()->json("Pergunta e resposta atualizadas com sucesso!", 200);
    }

    public function perguntaEstado($id) {
        //$pergunta = $this->pergunta->find($id);
        $result = Pergunta::where('id', $id)->first();
        if($result->pergunta_estado == 0){
            return response()->json(['msg'=>'Pergunta offline', 'perguntaEstado' => $result->pergunta_estado]);
        }
        return response()->json(['msg'=>'Pergunta online', 'perguntaEstado' => $result->pergunta_estado]);
    }

    public function perguntaOnline1($id) {

        $pergunta = $this->pergunta->find($id);

        $pergunta->pergunta_estado = 1;

        $pergunta->save();

        return response()->json("Pergunta online com sucesso!", 200);
    }

    public function perguntaOffline0($id) {

        $pergunta = $this->pergunta->find($id);

        $pergunta->pergunta_estado = 0;

        $pergunta->save();

        return response()->json("Pergunta offline com sucesso!", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pergunta  $pergunta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'Pergunta não existe.'], 404);
        }
        $pergunta->delete();
        return ['msg' => 'Pergunta removida'];
    }

    public function destroyTogether($id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'Pergunta não existe.'], 404);
        }

        $resposta = Resposta::where('pergunta_id', $id)->first();
        $resposta->delete();
        $pergunta->delete();

        return ['msg' => 'Pergunta e resposta removidas.'];
    }

    public function getData() {
        $perguntas = Pergunta::with('tema', 'resposta')->get();
        $temas = Tema::all();
        $icones = Icone::all();

        return response()->json([
            'perguntas' => $perguntas,
            'temas' => $temas,
            'icones' => $icones,
        ]);
    }

    public function indexFaq() {
        $result = DB::table('temas')
        ->join('perguntas', 'temas.id', '=', 'perguntas.tema_id')
        ->join('respostas', 'perguntas.id', '=', 'respostas.pergunta_id')
        ->select('temas.tema', 'temas.icone', 'perguntas.id', 'perguntas.pergunta', 'respostas.resposta')
        ->orderBy('perguntas.id', 'desc')
        ->get();

        return response()->json($result);
    }

    public function retornaTemas() {
        $result = DB::table('temas')
        ->select('temas.id', 'temas.tema', 'temas.icone')
        ->get();

        return response()->json($result);
    }

    public function retornaPerguntasOffline() {
        $result = Pergunta::where('pergunta_estado', 0)->with('tema', 'resposta')->get();

        return response()->json($result);
    }

    public function retornaPerguntasOnline() {
        $result = Pergunta::where('pergunta_estado', 1)->with('tema','resposta')->get();

        return response()->json($result);
    }

    public function retornaPerguntasAluno() {
        $result = Pergunta::where('pergunta_sugerida', 1)->with('resposta')->get();

        return response()->json($result);
    }

    public function getDatas() {
        $perguntasAluno = $this->retornaPerguntasAluno();
        $perguntasOffline = $this->retornaPerguntasOffline();
        // $perguntas = $this->indexFaq();
        $perguntas = $this->retornaPerguntasOnline();
        $temas = $this->retornaTemas();
        $icones = Icone::all();

        return response()->json([
            'perguntasAluno' => $perguntasAluno,
            'perguntasOffline' => $perguntasOffline,
            'perguntasOnline' => $perguntas,
            'temas' => $temas,
            'icones' => $icones,
        ]);
    }

    public function getDatasComFiltro(Request $request) {
        $perguntaRepository = new PerguntaRepository($this->pergunta);
        if($request->has('filtro')){
            $perguntaRepository->filtro($request->filtro);
        }
        $perguntaRepository = $this->indexFaq();


        return response()->json([
            'perguntas' => $perguntaRepository,

        ]);
    }

}
