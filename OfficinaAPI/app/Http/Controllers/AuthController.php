<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    /**
     * Registra um novo usuário.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'senha' => 'required|string|min:8',
            'role' => 'required|string',
            'documento' => 'nullable|string',
        ]);

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'role' => $request->role,
            'documento' => $request->documento,
        ]);

        return response()->json(['message' => 'Usuário registrado com sucesso'], 201);
    }

    /**
     * Autentica o usuário e inicia a sessão.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'senha' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['senha']])) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login realizado com sucesso'], 200);
        }

        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    /**
     * Encerra a sessão do usuário.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }

    /**
     * Retorna os dados do usuário autenticado.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}