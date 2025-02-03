<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicoPrestado;
use App\Models\ViaturaServico;
use App\Models\Viatura;
use App\Models\Servico;
use Illuminate\Support\Facades\DB;

class ServicoPrestadoController extends Controller
{
    /**
     * Registrar um serviço prestado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'viatura_id' => 'required|integer',
            'data' => 'required|date',
            'tecnico' => 'required|string',
            'observacoes' => 'nullable|string',
            'servicos' => 'required|array',
            'servicos.*.servico_id' => 'required|integer|exists:servicos,id',
            'servicos.*.quantidade' => 'required|integer|min:1'
        ]);

        return DB::transaction(function () use ($request) {
            // Criar o serviço prestado
            $servicoPrestado = ServicoPrestado::create([
                'viatura_id' => $request->viatura_id,
                'data' => $request->data,
                'tecnico' => $request->tecnico,
                'observacoes' => $request->observacoes,
                'valor_total' => 0
            ]);

            $valorTotal = 0;

            foreach ($request->servicos as $servico) {
                $preco = Servico::find($servico['servico_id'])->preco;
                $valorItem = $preco * $servico['quantidade'];
                $valorTotal += $valorItem;

                ViaturaServico::create([
                    'servico_prestado_id' => $servicoPrestado->id,
                    'servico_id' => $servico['servico_id'],
                    'quantidade' => $servico['quantidade'],
                    'valor_unitario' => $preco
                ]);
            }

            // Atualizar valor total do serviço prestado
            $servicoPrestado->update(['valor_total' => $valorTotal]);

            return response()->json($servicoPrestado->load('viaturaServico'), 201);
        });
    }
}