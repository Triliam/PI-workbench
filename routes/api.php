<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });




//ROTAS PUBLICAS
//Route::get("tpr/{id}", "App\Http\Controllers\TemaController@getEmCascata");

//rotas notificaçoes ANALIZAR!!!!
//rotas forçam a mudança das colunas pergunta_atualizacao e pergunta_estado
// Route::post('notf.0/{id}', "App\Http\Controllers\PerguntaController@perguntaAtualizacao0");
// Route::get('notf.1/{id}', "App\Http\Controllers\PerguntaController@perguntaAtualizacao1");
// Route::get('per.on.1/{id}', "App\Http\Controllers\PerguntaController@perguntaOnline1");
// Route::get('per.off.0/{id}', "App\Http\Controllers\PerguntaController@perguntaOffline0");

//Rotas notificações que exibem o status das perguntas
Route::get('notf/{id}', "App\Http\Controllers\PerguntaController@perguntaAtualizacao");
Route::get('est/{id}', "App\Http\Controllers\PerguntaController@perguntaEstado");

Route::post('esqueci-minha-senha','App\Http\Controllers\EmailController@enviarNovaSenha');


//Route::get('3f', "App\Http\Controllers\PerguntaController@getDatasComFiltro");
//Route::get('pa', "App\Http\Controllers\PerguntaController@retornaPerguntaAtualizacao");

    Route::get('3.1', "App\Http\Controllers\PerguntaController@getDatas");
    Route::get('3', "App\Http\Controllers\PerguntaController@getData");

    Route::get("faqs", "App\Http\Controllers\FaqsController@index");

    Route::get("user", "App\Http\Controllers\UserController@index");
    Route::get("user/{user}", "App\Http\Controllers\UserController@show");
    Route::post("user", "App\Http\Controllers\UserController@store");


    Route::get("tema", "App\Http\Controllers\TemaController@index");
    Route::get("tema/{tema}", "App\Http\Controllers\TemaController@show");

    Route::get("pergunta", "App\Http\Controllers\PerguntaController@index");
    Route::get("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@show");

    Route::get("resposta", "App\Http\Controllers\RespostaController@index");
    Route::get("resposta/{resposta}", "App\Http\Controllers\RespostaController@show");

    Route::post('logint', 'App\Http\Controllers\AuthController@loginToken');
    Route::post('login', 'App\Http\Controllers\AuthController@login');



//Prefix pra l0 para aluno, l1 colab, l2 adm

//ROTAS ADM
Route::prefix('l2')->middleware('jwt.auth')->group(function() {

    Route::post('esqueci-minha-senha', 'App\Http\Controllers\EmailController@enviarNovaSenha');

    Route::patch('users/block/{id}', 'App\Http\Controllers\UserController@blockUser');

    Route::get("pergs", "App\Http\Controllers\PerguntaController@retorna");

    Route::get("users", "App\Http\Controllers\UserController@getUsersWithLevelOne");

    Route::get("icones", "App\Http\Controllers\IconeController@index");
    Route::post("icones", "App\Http\Controllers\IconeController@store");
    Route::patch("icones", "App\Http\Controllers\IconeController@update");

    Route::post("pr", "App\Http\Controllers\PerguntaController@storeTogether");
    Route::patch("updatepr/{pergunta}", "App\Http\Controllers\PerguntaController@updateTogether");
    Route::delete("delpr/{pergunta}", "App\Http\Controllers\PerguntaController@destroyTogether");

    Route::post("user", "App\Http\Controllers\UserController@store");
    Route::patch("user/{user}", "App\Http\Controllers\UserController@update");
    Route::put("user/{user}", "App\Http\Controllers\UserController@update");
    Route::delete("user/{user}", "App\Http\Controllers\UserController@destroy");
    Route::get("users", "App\Http\Controllers\UserController@getUsersWithLevelOne");

    Route::post("tema", "App\Http\Controllers\TemaController@store");
    Route::patch("tema/{tema}", "App\Http\Controllers\TemaController@update");
    Route::put("tema/{tema}", "App\Http\Controllers\TemaController@update");
    Route::delete("tema/{tema}", "App\Http\Controllers\TemaController@destroy");
    Route::delete("temac/{tema}", "App\Http\Controllers\TemaController@deleteOnCascate");

    // Route::post("pergunta", "App\Http\Controllers\PerguntaController@store");
    // Route::patch("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
    // Route::put("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
    // Route::delete("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@destroy");

    // Route::post("resposta", "App\Http\Controllers\RespostaController@store");
    // Route::patch("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
    // Route::put("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
    // Route::delete("resposta/{resposta}", "App\Http\Controllers\RespostaController@destroy");

    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::get('me', 'App\Http\Controllers\AuthController@me');
});




    //ROTAS COLABORADORES
    Route::prefix('l1')->middleware('jwt.auth')->group(function() {

        Route::post('esqueci-minha-senha', 'App\Http\Controllers\EmailController@enviarNovaSenha');
        Route::patch('users/block/{id}', 'App\Http\Controllers\UserController@blockUser');

        Route::post("user", "App\Http\Controllers\UserController@store");
        Route::patch("user/{user}", "App\Http\Controllers\UserController@update");
        Route::put("user/{user}", "App\Http\Controllers\UserController@update");
        Route::delete("user/{user}", "App\Http\Controllers\UserController@destroy");
        Route::get("users", "App\Http\Controllers\UserController@getUsersWithLevelOne");
        Route::patch('users/block/{id}', 'App\Http\Controllers\UserController@blockUser');

        Route::post("tema", "App\Http\Controllers\TemaController@store");
        Route::patch("tema/{tema}", "App\Http\Controllers\TemaController@update");
        Route::put("tema/{tema}", "App\Http\Controllers\TemaController@update");
        Route::delete("tema/{tema}", "App\Http\Controllers\TemaController@destroy");

        Route::post("pr", "App\Http\Controllers\PerguntaController@storeTogether");
        Route::patch("updatepr/{pergunta}", "App\Http\Controllers\PerguntaController@updateTogether");
        Route::delete("delpr/{pergunta}", "App\Http\Controllers\PerguntaController@destroyTogether");

        // Route::post("pergunta", "App\Http\Controllers\PerguntaController@store");
        // Route::patch("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
        // Route::put("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
        // Route::delete("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@destroy");

        // Route::post("resposta", "App\Http\Controllers\RespostaController@store");
        // Route::patch("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
        // Route::put("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
        // Route::delete("resposta/{resposta}", "App\Http\Controllers\RespostaController@destroy");

        Route::post('logout', 'App\Http\Controllers\AuthController@logout');
        Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
        Route::post('me', 'App\Http\Controllers\AuthController@me');
        Route::get('me', 'App\Http\Controllers\AuthController@me');

    });

    //ROTAS ALUNOS
    Route::prefix('l0')->middleware('jwt.auth')->group(function() {

        Route::post('esqueci-minha-senha', 'App\Http\Controllers\EmailController@enviarNovaSenha');
        Route::post("visualizado/{id}", "App\Http\Controllers\PerguntaController@atualizacao0");

        Route::patch("user/{user}", "App\Http\Controllers\UserController@update");
        Route::post("pergs", "App\Http\Controllers\PerguntaController@storeAluno");
        Route::post('logout', 'App\Http\Controllers\AuthController@logout');
        Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
        Route::post('me', 'App\Http\Controllers\AuthController@me');
        Route::get('me', 'App\Http\Controllers\AuthController@me');
    });
