<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registrar um novo usuário.
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
            'senha' => Hash::make($request->senha), // Criptografa a senha
            'role' => $request->role,
            'documento' => $request->documento,
        ]);

        // Cria um token de acesso para o usuário
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Fazer login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'senha' => 'required|string',
        ]);

        // Verifica as credenciais
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->senha])) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        // Obtém o usuário autenticado
        $usuario = Usuario::where('email', $request->email)->firstOrFail();

        // Cria um token de acesso para o usuário
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Fazer logout.
     */
    public function logout(Request $request)
    {
        // Revoga todos os tokens do usuário
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Obter informações do usuário autenticado.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}