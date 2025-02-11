<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viatura extends Model
{
    use HasFactory;

        /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'marca',
        'modelo',
        'cor',
        'tipo_avaria',
        'estado',
        'codigo_validacao',
        'cliente_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'viatura_servico')->withPivot('data');
    }
    
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'viatura_id');
    }
    public function ordensServico()
    {
        return $this->hasMany(OrdemServico::class);
    }
}
