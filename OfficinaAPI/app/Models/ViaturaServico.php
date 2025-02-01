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
        'servico_prestado_id',
        'servico_id',
        'quantidade',
        'valor_unitario',
    ];

        // Relação com a tabela servicos_prestados
        public function servicoPrestado()
        {
            return $this->belongsTo(ServicoPrestado::class);
        }
    
        // Relação com a tabela servicos
        public function servico()
        {
            return $this->belongsTo(Servico::class);
        }
}