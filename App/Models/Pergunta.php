<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    use HasFactory;
    protected $fillable = ['tema_id', 'user_id', 'pergunta', 'pergunta_sugerida'];
    protected $casts = [
        'pergunta_sugerida' => 'boolean',
    ];
    
    public function rules () {
        return [
            'tema_id' => 'exists:temas,id',
            //'pergunta' => 'required|unique:perguntas,pergunta,'.$this->id];
            //'pergunta' => 'unique:perguntas,pergunta,'.$this->id];
            'pergunta' => 'required:perguntas,pergunta,'.$this->id];
    }

    public function feedback () {
        return [

            // 'required' => 'O campo :attribute é obrigatorio', 'pergunta.unique' => 'A pergunta já existe'];

            'required' => 'O campo :attribute é obrigatorio'];
    }


    public function tema() {
        //uma pergunta pertence a um tema
        return $this->belongsTo('App\Models\Tema');
    }

    public function userPergunta() {
        //uma pergunta pertence a um user
        return $this->belongsTo('App\Models\User');
    }

   public function resposta() {
    //uma pergunta tem uma resposta
    return $this->hasOne('App\Models\Resposta');
   }
}
