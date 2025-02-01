<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicoPrestado extends Model
{
    use HasFactory;

    protected $table = 'servicos_prestados';

    protected $fillable = [
        'viatura_id',
        'data',
        'valor_total',
        'tecnico', 
        'observacoes', 
    ];

    // Relação com a tabela viaturas
    public function viatura()
    {
        return $this->belongsTo(Viatura::class);
    }

    // Relação com a tabela viatura_servico
    public function servicos()
    {
        return $this->hasMany(ViaturaServico::class);
    }

    // Método para calcular o valor total do serviço prestado
    public function calcularValorTotal()
    {
        $this->valor_total = $this->servicos->sum(function ($servico) {
            return $servico->quantidade * $servico->valor_unitario;
        });

        $this->save();
    }
}