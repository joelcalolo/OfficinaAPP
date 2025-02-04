<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\OrdemServico;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index()
    {
        $pagamentos = Pagamento::with(['ordemServico.viatura.cliente', 'ordemServico.servicos'])->get();
        return view('pagamentos.index', compact('pagamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ordem_servico_id' => 'required|exists:ordem_servicos,id',
        ]);

        $ordemServico = OrdemServico::findOrFail($request->ordem_servico_id);

        // Cria um único pagamento para toda a ordem de serviço
        $pagamento = Pagamento::create([
            'valor' => $ordemServico->total,
            'data' => now(),
            'status' => 'pendente',
            'ordem_servico_id' => $ordemServico->id,
        ]);

        return redirect()->back()->with('success', 'Pagamento gerado com sucesso!');
    }

    public function confirmarPagamento(Request $request)
    {
        $request->validate([
            'codigo_referencia' => 'required|exists:pagamentos,codigo_referencia'
        ]);

        $pagamento = Pagamento::where('codigo_referencia', $request->codigo_referencia)
            ->where('status', 'pendente')
            ->firstOrFail();

        $pagamento->update([
            'status' => 'pago',
            'data' => now()
        ]);

        return redirect()->back()->with('success', 'Pagamento confirmado com sucesso!');
    }

    public function show($id)
    {
        $pagamento = Pagamento::with(['ordemServico.viatura.cliente', 'ordemServico.servicos.servico', 'ordemServico.mecanico'])
            ->findOrFail($id);
        return view('pagamentos.show', compact('pagamento'));
    }
}