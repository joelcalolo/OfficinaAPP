<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViaturaServico extends Model
{
    use HasFactory;

    // Define o nome da tabela
    protected $table = 'viatura_servico';

    protected $fillable = [
        'viatura_id',
        'servico_id',
        'data',
    ];
}