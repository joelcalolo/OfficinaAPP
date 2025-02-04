<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Viatura;
use App\Models\OrdemServico;

class RelatorioController extends Controller
{
    public function index()
    {
        $viaturas = Viatura::all();
        $clientes = Usuario::where('role', 'cliente')->get();
        $tecnicos = Usuario::where('role', 'tecnico')->get();

        return view('relatorios.index', compact('viaturas', 'clientes', 'tecnicos'));
    }

    public function gerarRelatorio(Request $request)
    {
        $query = OrdemServico::with(['viatura', 'viatura.cliente', 'mecanico', 'servicos']);

        if ($request->data_inicio) {
            $query->whereDate('data_servico', '>=', $request->data_inicio);
        }

        if ($request->data_fim) {
            $query->whereDate('data_servico', '<=', $request->data_fim);
        }

        if ($request->viatura_id) {
            $query->where('viatura_id', $request->viatura_id);
        }

        if ($request->cliente_id) {
            $query->whereHas('viatura', function($q) use ($request) {
                $q->where('cliente_id', $request->cliente_id);
            });
        }

        if ($request->tecnico_id) {
            $query->where('mecanico_id', $request->tecnico_id);
        }

        $ordens = $query->get();

        return response()->json([
            'html' => view('relatorios.partials.resultado', compact('ordens'))->render()
        ]);
    }

    public function exportarPDF(Request $request)
    {
        // Implementar a lógica de exportação para PDF
        // Você precisará de um pacote como dompdf ou similar
    }
}