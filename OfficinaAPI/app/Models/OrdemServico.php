<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    // Definindo os campos que podem ser preenchidos
    protected $fillable = [
        'viatura_id',
        'mecanico_id',
        'data_servico',
        'total'
    ];

    // Relacionamento com Veículo (Um para Muitos)
    public function viatura()
    {
        return $this->belongsTo(Viatura::class);
    }

    // Relacionamento com Mecânico (Usuário) (Um para Muitos)
    public function mecanico()
    {
        return $this->belongsTo(Usuario::class, 'mecanico_id');
    }

    // Relacionamento com OrdemServicoServico (Um para Muitos)
    public function servicos()
    {
        return $this->hasMany(OrdemServicoServico::class);
    }

    // Método para calcular o total da ordem de serviço
    public function calcularTotal()
    {
        return $this->servicos->sum(function($servico) {
            return $servico->preco_unitario * $servico->quantidade;
        });
    }
}
