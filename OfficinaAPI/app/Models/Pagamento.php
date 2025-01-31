<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'valor',
        'data',
        'viatura_id',
        'servico_id',
    ];

    /**
     * Obtém a viatura associada ao pagamento.
     */
    public function viatura()
    {
        return $this->belongsTo(Viatura::class, 'viatura_id');
    }

    /**
     * Obtém o serviço associado ao pagamento.
     */
    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }
}