<?php

namespace App\Http\Controllers;

use App\Models\Viatura;
use Illuminate\Http\Request;

class ViaturaController extends Controller
{
    /**
     * Listar todas as viaturas.
     */
    public function index()
    {
        $viaturas = Viatura::all();
        return response()->json($viaturas);
    }

    /**
     * Criar uma nova viatura.
     */
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

        return response()->json($viatura, 201);
    }

    /**
     * Obter detalhes de uma viatura.
     */
    public function show($id)
    {
        $viatura = Viatura::find($id);
        if (!$viatura) {
            return response()->json(['message' => 'Viatura não encontrada'], 404);
        }
        return response()->json($viatura);
    }

    /**
     * Atualizar uma viatura.
     */
    public function update(Request $request, $id)
    {
        $viatura = Viatura::find($id);
        if (!$viatura) {
            return response()->json(['message' => 'Viatura não encontrada'], 404);
        }

        $request->validate([
            'marca' => 'sometimes|string|max:255',
            'modelo' => 'sometimes|string|max:255',
            'cor' => 'sometimes|string|max:255',
            'tipo_avaria' => 'sometimes|string|max:255',
            'estado' => 'sometimes|string|max:255',
            'codigo_validacao' => 'sometimes|string|max:255|unique:viaturas,codigo_validacao,' . $viatura->id,
            'cliente_id' => 'sometimes|exists:usuarios,id',
        ]);

        $viatura->update($request->all());

        return response()->json($viatura);
    }

    /**
     * Excluir uma viatura.
     */
    public function destroy($id)
    {
        $viatura = Viatura::find($id);
        if (!$viatura) {
            return response()->json(['message' => 'Viatura não encontrada'], 404);
        }

        $viatura->delete();

        return response()->json(['message' => 'Viatura deletada com sucesso']);
    }

     /**
     * Gerar código QR para uma viatura concluída.
     */
    public function gerarQrCode($id)
    {
        $viatura = Viatura::find($id);

        if (!$viatura) {
            return response()->json(['message' => 'Viatura não encontrada'], 404);
        }

        // Verifica se a viatura está concluída
        if ($viatura->estado !== 'Concluído') {
            return response()->json(['message' => 'A viatura não está concluída'], 400);
        }

        // Gera o conteúdo do QR Code (pode ser um link, texto, etc.)
        $qrContent = "Viatura ID: {$viatura->id}\nMarca: {$viatura->marca}\nModelo: {$viatura->modelo}\nEstado: {$viatura->estado}";

        // Gera o QR Code em formato PNG
        $qrCode = QrCode::format('png')->size(300)->generate($qrContent);

        // Retorna o QR Code como resposta
        return response($qrCode)->header('Content-Type', 'image/png');
    }
}