<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicoPrestado;
use App\Models\ViaturaServico;
use App\Models\Viatura;
use App\Models\Servico;

class ServicoPrestadoController extends Controller
{
    /**
     * Registrar um serviço prestado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'viatura_id' => 'required|exists:viaturas,id',
            'data' => 'required|date',
            'tecnico' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string',
            'servicos' => 'required|array',
            'servicos.*.servico_id' => 'required|exists:servicos,id',
            'servicos.*.quantidade' => 'required|integer|min:1',
            'servicos.*.valor_unitario' => 'required|numeric|min:0',
        ]);
    
        // Cria o serviço prestado
        $servicoPrestado = ServicoPrestado::create([
            'viatura_id' => $request->viatura_id, // viatura_id vai para a tabela servicos_prestados
            'data' => $request->data,
            'tecnico' => $request->tecnico,
            'observacoes' => $request->observacoes,
            'valor_total' => 0, // Inicializa o valor total como 0
        ]);
    
        // Adiciona os serviços ao serviço prestado
        foreach ($request->servicos as $servico) {
            ViaturaServico::create([
                'servico_prestado_id' => $servicoPrestado->id, // Relaciona com servicos_prestados
                'servico_id' => $servico['servico_id'],
                'quantidade' => $servico['quantidade'],
                'valor_unitario' => $servico['valor_unitario'],
            ]);
        }
    
        // Calcula o valor total do serviço prestado
        $servicoPrestado->calcularValorTotal();
    
        return response()->json($servicoPrestado, 201);
    }
}