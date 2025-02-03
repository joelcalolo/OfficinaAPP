<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
        'preco',
    ];

    public function viaturas()
    {
        return $this->belongsToMany(Viatura::class, 'viatura_servico')->withPivot('data');
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'servico_id');
    }

    public function ordensServico()
    {
        return $this->hasMany(OrdemServicoServico::class);
    }
}
