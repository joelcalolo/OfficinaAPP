<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\OrdemServicoServico;
use App\Models\Viatura;
use App\Models\Servico;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdemServicoController extends Controller
{
    public function index()
    {
        $ordensServico = OrdemServico::with(['viatura', 'viatura.cliente', 'mecanico', 'servicos.servico'])->get();
        $viaturas = Viatura::with('cliente')->get();
        $servicos = Servico::all();
        $mecanicos = Usuario::where('role', 'tecnico')->get();

        return view('ordens-servico.index', compact('ordensServico', 'viaturas', 'servicos', 'mecanicos'));
    }

    public function show($id)
    {
        $ordem = OrdemServico::with(['viatura', 'viatura.cliente', 'mecanico', 'servicos.servico'])
            ->findOrFail($id);
            
        return view('ordens-servico.show', compact('ordem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'viatura_id' => 'required|exists:viaturas,id',
            'mecanico_id' => 'required|exists:usuarios,id',
            'servicos' => 'required|array',
            'servicos.*.servico_id' => 'required|exists:servicos,id',
            'servicos.*.quantidade' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $ordemServico = OrdemServico::create([
                'viatura_id' => $request->viatura_id,
                'mecanico_id' => $request->mecanico_id,
                'data_servico' => now(),
                'status' => 'Em Análise',
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->servicos as $servicoData) {
                $servico = Servico::find($servicoData['servico_id']);
                $preco_unitario = $servico->preco;
                $subtotal = $preco_unitario * $servicoData['quantidade'];
                $total += $subtotal;

                OrdemServicoServico::create([
                    'ordem_servico_id' => $ordemServico->id,
                    'servico_id' => $servicoData['servico_id'],
                    'quantidade' => $servicoData['quantidade'],
                    'preco_unitario' => $preco_unitario
                ]);
            }

            $ordemServico->update(['total' => $total]);
        });

        return redirect()->route('ordens-servico.index')
            ->with('success', 'Ordem de serviço criada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:Em Análise,Em Andamento,Concluído',
            'mecanico_id' => 'required|exists:usuarios,id',
        ]);

        $ordemServico->update($request->all());

        return redirect()->route('ordens-servico.index')
            ->with('success', 'Ordem de serviço atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $ordemServico->delete();

        return redirect()->route('ordens-servico.index')
            ->with('success', 'Ordem de serviço excluída com sucesso!');
    }
}