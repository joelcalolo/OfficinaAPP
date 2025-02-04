<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordem_servicos';

    protected $fillable = [
        'viatura_id',
        'mecanico_id',
        'data_servico',
        'total',
        'status'
    ];

    public function viatura()
    {
        return $this->belongsTo(Viatura::class);
    }

    public function mecanico()
    {
        return $this->belongsTo(Usuario::class, 'mecanico_id');
    }

    public function servicos()
    {
        return $this->hasMany(OrdemServicoServico::class);
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class);
    }

    public function calcularTotal()
    {
        return $this->servicos->sum(function($servico) {
            return $servico->preco_unitario * $servico->quantidade;
        });
    }
}