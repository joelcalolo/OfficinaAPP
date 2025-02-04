<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'senha' => 'required|string|min:8',
            'role' => 'required|string',
            'documento' => 'required|file|mimes:pdf|max:2048', // Máximo 2MB
        ]);

        try {
            // Upload do documento
            $documentoPath = null;
            if ($request->hasFile('documento')) {
                $documentoPath = $request->file('documento')->store('documentos', 'public');
            }

            $usuario = Usuario::create([
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => Hash::make($request->senha),
                'role' => $request->role,
                'documento' => $documentoPath,
            ]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            // Se houver erro, remove o arquivo se foi feito upload
            if ($documentoPath && Storage::disk('public')->exists($documentoPath)) {
                Storage::disk('public')->delete($documentoPath);
            }

            return redirect()->route('usuarios.index')
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,'.$id,
            'senha' => 'nullable|string|min:8',
            'role' => 'required|string',
            'documento' => 'nullable|file|mimes:pdf|max:2048', // Máximo 2MB
        ]);

        try {
            $data = $request->except(['senha', 'documento']);
            
            if ($request->filled('senha')) {
                $data['senha'] = Hash::make($request->senha);
            }

            // Upload do novo documento
            if ($request->hasFile('documento')) {
                // Remove o documento antigo se existir
                if ($usuario->documento) {
                    Storage::disk('public')->delete($usuario->documento);
                }
                $data['documento'] = $request->file('documento')->store('documentos', 'public');
            }

            $usuario->update($data);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            
            // Remove o documento se existir
            if ($usuario->documento) {
                Storage::disk('public')->delete($usuario->documento);
            }
            
            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }

    public function downloadDocumento($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        if (!$usuario->documento) {
            return back()->with('error', 'Documento não encontrado.');
        }

        return Storage::disk('public')->download($usuario->documento);
    }
}