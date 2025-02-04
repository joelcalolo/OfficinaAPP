<?php

namespace App\Http\Controllers;

use App\Models\Viatura;
use App\Models\Usuario;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ViaturaController extends Controller
{
    public function index()
    {
        $viaturas = Viatura::with('cliente')->get();
        return view('viaturas.index', compact('viaturas'));
    }

    public function create()
    {
        $clientes = Usuario::where('role', 'cliente')->get();
        return view('viaturas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'cor' => 'required|string|max:255',
            'tipo_avaria' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'codigo_validacao' => 'required|string|max:255|unique:viaturas',
            'cliente_id' => 'required|exists:usuarios,id',
        ]);

        $viatura = Viatura::create($request->all());

        return redirect()->route('viaturas.index')
            ->with('success', 'Viatura cadastrada com sucesso!');
    }

    public function show($id)
    {
        $viatura = Viatura::with(['cliente', 'ordensServico.servicos.servico', 'ordensServico.mecanico'])
            ->findOrFail($id);
        return view('viaturas.show', compact('viatura'));
    }

    public function edit($id)
    {
        $viatura = Viatura::findOrFail($id);
        $clientes = Usuario::where('role', 'cliente')->get();
        return view('viaturas.edit', compact('viatura', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'cor' => 'required|string|max:255',
            'tipo_avaria' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'codigo_validacao' => 'required|string|max:255|unique:viaturas,codigo_validacao,'.$id,
            'cliente_id' => 'required|exists:usuarios,id',
        ]);

        $viatura = Viatura::findOrFail($id);
        $viatura->update($request->all());

        return redirect()->route('viaturas.index')
            ->with('success', 'Viatura atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $viatura = Viatura::findOrFail($id);
        $viatura->delete();

        return redirect()->route('viaturas.index')
            ->with('success', 'Viatura excluída com sucesso!');
    }

    public function gerarQrCode($id)
    {
        $viatura = Viatura::findOrFail($id);

        if ($viatura->estado !== 'Concluído') {
            return response()->json(['message' => 'A viatura não está concluída'], 400);
        }

        $qrContent = "Viatura ID: {$viatura->id}\nMarca: {$viatura->marca}\nModelo: {$viatura->modelo}\nEstado: {$viatura->estado}";
        $qrCode = QrCode::format('png')->size(300)->generate($qrContent);

        return response($qrCode)->header('Content-Type', 'image/png');
    }

    public function minhasViaturas()
    {
        $viaturas = Viatura::with(['ordensServico.servicos.servico'])
            ->where('cliente_id', auth()->id())
            ->get();
        
        return view('viaturas.minhas-viaturas', compact('viaturas'));
    }
}