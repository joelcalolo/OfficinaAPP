<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * Listar todos os pagamentos.
     */
    public function index()
    {
        $pagamentos = Pagamento::all();
        return response()->json($pagamentos);
    }

    /**
     * Criar um novo pagamento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'data' => 'required|date',
            'viatura_id' => 'required|exists:viaturas,id',
            'servico_id' => 'required|exists:servicos,id',
        ]);

        $pagamento = Pagamento::create($request->all());

        return response()->json($pagamento, 201);
    }

    /**
     * Obter detalhes de um pagamento.
     */
    public function show($id)
    {
        $pagamento = Pagamento::find($id);
        if (!$pagamento) {
            return response()->json(['message' => 'Pagamento não encontrado'], 404);
        }
        return response()->json($pagamento);
    }

    /**
     * Atualizar um pagamento.
     */
    public function update(Request $request, $id)
    {
        $pagamento = Pagamento::find($id);
        if (!$pagamento) {
            return response()->json(['message' => 'Pagamento não encontrado'], 404);
        }

        $request->validate([
            'valor' => 'sometimes|numeric|min:0',
            'data' => 'sometimes|date',
            'viatura_id' => 'sometimes|exists:viaturas,id',
            'servico_id' => 'sometimes|exists:servicos,id',
        ]);

        $pagamento->update($request->all());

        return response()->json($pagamento);
    }

    /**
     * Excluir um pagamento.
     */
    public function destroy($id)
    {
        $pagamento = Pagamento::find($id);
        if (!$pagamento) {
            return response()->json(['message' => 'Pagamento não encontrado'], 404);
        }

        $pagamento->delete();

        return response()->json(['message' => 'Pagamento deletado com sucesso']);
    }
}