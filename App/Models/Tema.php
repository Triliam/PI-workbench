<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','tema','icone'];

    public function rules () {
        return [ 'tema' => 'required|unique:temas,tema,'.$this->id];
    }

    public function feedback () {
        return ['required' => 'O campo :attribute é obrigatorio', 'tema.unique' => 'O tema já existe.'];
    }

    public function user() {
        //um tema pertence a um user
        return $this->belongsTo('App\Models\User');
    }

    public function perguntas() {
        //um tema possui varias perguntas
        return $this->hasMany('App\Models\Pergunta');
    }
}
