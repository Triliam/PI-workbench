<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function rules () {
    //     return [
    //         'password' =>'required:users,password',
    //         'email' => 'required|unique:users,email,'.$this->id,
    //         'regex:/^[a-zA-Z0-9._%+-]+@fatec\.sp\.gov\.br$/i']; // Adiciona a regra de regex
    // }

    public function rules()
{
    return [
        'password' => 'required:users,password',
        'email' => [
            'required',
            'unique:users,email,' . $this->id,
            'regex:/^[a-zA-Z0-9._%+-]+@fatec\.sp\.gov\.br$/i', // Adiciona a regra de regex
        ],
    ];
}

    public function feedback () {
        return [
            'required' => 'O campo :attribute é obrigatorio',
            'email.unique' => 'O email já existe.',
             'email.regex' => 'O e-mail deve ser do domínio @fatec.sp.gov.br'];
    }
}


