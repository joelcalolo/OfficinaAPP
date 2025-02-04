<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'senha' => 'required|string|min:8',
            'role' => 'required|string',
            'documento' => 'required|file|mimes:pdf|max:2048',
        ]);

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

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'usuario' => $usuario
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'senha' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->senha])) {
            return back()->with('error', 'Credenciais inválidas');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}