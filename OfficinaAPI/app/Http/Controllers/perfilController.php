<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,'.$user->id,
            'senha_atual' => 'nullable|string',
            'nova_senha' => 'nullable|string|min:8|required_with:senha_atual',
            'confirmar_senha' => 'nullable|string|same:nova_senha',
            'documento' => 'nullable|string|max:255',
        ]);

        if ($request->senha_atual) {
            if (!Hash::check($request->senha_atual, $user->senha)) {
                return back()->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
            }
        }

        $user->nome = $request->nome;
        $user->email = $request->email;
        
        if ($request->nova_senha) {
            $user->senha = Hash::make($request->nova_senha);
        }

        if ($user->role == 'cliente' && $request->has('documento')) {
            $user->documento = $request->documento;
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Validar senha atual
        if (!Hash::check($request->senha_confirmacao, $user->senha)) {
            return back()->withErrors(['senha_confirmacao' => 'Senha incorreta.']);
        }

        // Fazer logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Deletar o usuário
        $user->delete();

        return redirect()->route('login')->with('success', 'Sua conta foi excluída com sucesso.');
    }
}