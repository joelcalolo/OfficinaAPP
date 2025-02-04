<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;
    
    protected $table = 'usuarios';
    
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'role',
        'documento'
    ];
    
    protected $hidden = [
        'senha',
        'remember_token'
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function veiculos()
    {
        return $this->hasMany(Viatura::class, 'cliente_id');
    }
}