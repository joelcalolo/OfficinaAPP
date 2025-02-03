<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;
use App\Models\OrdemServicoServico;
use App\Models\Viatura;
use App\Models\Servico;

class OrdemServicoController extends Controller
{
    /**
     * Criar uma nova ordem de serviço.
     */
    public function store(Request $request)
    {
        $request->validate([
            'viatura_id' => 'required|exists:viaturas,id',
            'mecanico_id' => 'required|exists:usuarios,id',
            'servicos' => 'required|array',
            'servicos.*.servico_id' => 'required|exists:servicos,id',
            'servicos.*.quantidade' => 'required|integer|min:1',
            'servicos.*.preco_unitario' => 'required|numeric|min:0'
        ]);

        // Criar a ordem de serviço
        $ordemServico = OrdemServico::create([
            'viatura_id' => $request->viatura_id,
            'mecanico_id' => $request->mecanico_id,
            'data_servico' => now(),
            'total' => 0 // Será atualizado depois
        ]);

        $total = 0;

        // Adicionar serviços à ordem de serviço
        foreach ($request->servicos as $servicoData) {
            OrdemServicoServico::create([
                'ordem_servico_id' => $ordemServico->id,
                'servico_id' => $servicoData['servico_id'],
                'quantidade' => $servicoData['quantidade'],
                'preco_unitario' => $servicoData['preco_unitario']
            ]);

            // Atualiza o total da OS
            $total += $servicoData['quantidade'] * $servicoData['preco_unitario'];
        }

        // Atualizar o total da ordem de serviço
        $ordemServico->update(['total' => $total]);

        return response()->json([
            'message' => 'Ordem de serviço criada com sucesso!',
            'ordem_servico' => $ordemServico->load('servicos')
        ], 201);
    }

    public function index()
    {
    $ordens = OrdemServico::with(['viatura', 'mecanico', 'servicos.servico'])->get();

    return response()->json($ordens, 200);
    }
    
    public function show($id)
    {
    $ordemServico = OrdemServico::with(['viatura', 'mecanico', 'servicos.servico'])->find($id);

    if (!$ordemServico) {
        return response()->json(['message' => 'Ordem de serviço não encontrada'], 404);
    }

    return response()->json($ordemServico, 200);
    }


}
