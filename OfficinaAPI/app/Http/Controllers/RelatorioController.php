<?php

namespace App\Http\Controllers;

use App\Models\Viatura;
use App\Models\Servico;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    /**
     * Gerar relatório de viaturas.
     */
    public function relatorioViaturas()
    {
        // Exemplo: Listar todas as viaturas com seus serviços associados
        $viaturas = Viatura::with('servicos')->get();

        // Formatar os dados para o relatório
        $relatorio = $viaturas->map(function ($viatura) {
            return [
                'id' => $viatura->id,
                'marca' => $viatura->marca,
                'modelo' => $viatura->modelo,
                'estado' => $viatura->estado,
                'servicos' => $viatura->servicos->map(function ($servico) {
                    return [
                        'id' => $servico->id,
                        'nome' => $servico->nome,
                        'preco' => $servico->preco,
                    ];
                }),
            ];
        });

        return response()->json($relatorio);
    }

    /**
     * Gerar relatório de serviços.
     */
    public function relatorioServicos()
    {
        // Exemplo: Listar todos os serviços com o número de viaturas associadas
        $servicos = Servico::withCount('viaturas')->get();

        // Formatar os dados para o relatório
        $relatorio = $servicos->map(function ($servico) {
            return [
                'id' => $servico->id,
                'nome' => $servico->nome,
                'descricao' => $servico->descricao,
                'preco' => $servico->preco,
                'numero_viaturas' => $servico->viaturas_count,
            ];
        });

        return response()->json($relatorio);
    }
}