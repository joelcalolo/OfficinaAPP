<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::all();
        return view('servicos.index', compact('servicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
        ]);

        $servico = Servico::create($request->all());

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço criado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $servico = Servico::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
        ]);

        $servico->update($request->all());

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $servico = Servico::findOrFail($id);
        $servico->delete();

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço excluído com sucesso!');
    }
}