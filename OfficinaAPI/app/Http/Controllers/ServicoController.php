<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    /**
     * Listar todos os serviços.
     */
    public function index()
    {
        $servicos = Servico::all();
        return response()->json($servicos);
    }

    /**
     * Criar um novo serviço.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
        ]);

        $servico = Servico::create($request->all());

        return response()->json($servico, 201);
    }

    /**
     * Obter detalhes de um serviço.
     */
    public function show($id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }
        return response()->json($servico);
    }

    /**
     * Atualizar um serviço.
     */
    public function update(Request $request, $id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        $request->validate([
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'sometimes|string',
            'preco' => 'sometimes|numeric|min:0',
        ]);

        $servico->update($request->all());

        return response()->json($servico);
    }

    /**
     * Excluir um serviço.
     */
    public function destroy($id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        $servico->delete();

        return response()->json(['message' => 'Serviço deletado com sucesso']);
    }
}