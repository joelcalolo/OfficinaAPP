<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServicoServico extends Model
{
    use HasFactory;

    // Definindo os campos que podem ser preenchidos
    protected $fillable = [
        'ordem_servico_id',
        'servico_id',
        'quantidade',
        'preco_unitario'
    ];

    // Relacionamento com OrdemServico (Muitos para Um)
    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class);
    }

    // Relacionamento com Serviço (Muitos para Um)
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    // Método para obter o valor total do serviço específico
    public function valorTotal()
    {
        return $this->preco_unitario * $this->quantidade;
    }
}
