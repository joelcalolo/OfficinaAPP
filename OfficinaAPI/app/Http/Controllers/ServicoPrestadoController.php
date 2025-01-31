<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viatura;
use App\Models\Servico;
use App\Models\ViaturaServico;

class ServicoPrestadoController extends Controller
{
    /**
     * Registrar um serviço prestado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'viatura_id' => 'required|exists:viaturas,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date',
        ]);

        // Verifica se a viatura e o serviço existem
        $viatura = Viatura::find($request->viatura_id);
        $servico = Servico::find($request->servico_id);

        if (!$viatura || !$servico) {
            return response()->json(['message' => 'Viatura ou serviço não encontrado'], 404);
        }

        // Registra o serviço prestado na tabela de relação
        $viaturaServico = ViaturaServico::create([
            'viatura_id' => $request->viatura_id,
            'servico_id' => $request->servico_id,
            'data' => $request->data,
        ]);

        return response()->json($viaturaServico, 201);
    }
}