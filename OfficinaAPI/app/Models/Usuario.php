<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'usuarios'; // Define o nome da tabela
    protected $fillable = ['nome', 'email', 'senha', 'role', 'documento']; // Campos que podem ser preenchidos
    protected $hidden = ['senha', 'remember_token']; // Campos ocultos

    public function getAuthPassword()
    {
        return $this->senha; // Define o campo de senha como 'senha'
    }
    public function veiculos(){
        return 
        $this->hasMany(Viatura::class);
    }
}
