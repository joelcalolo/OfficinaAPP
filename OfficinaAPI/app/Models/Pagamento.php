<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor',
        'data',
        'codigo_referencia',
        'status',
        'ordem_servico_id'
    ];

    protected static function boot()
    {
        parent::boot();

        // Gera código de referência único antes de criar o pagamento
        static::creating(function ($pagamento) {
            do {
                $codigo = strtoupper(Str::random(10)); // Gera código de 10 caracteres
            } while (static::where('codigo_referencia', $codigo)->exists());
            
            $pagamento->codigo_referencia = $codigo;
        });
    }

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class);
    }
}