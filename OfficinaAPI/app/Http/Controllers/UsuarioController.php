<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Lista todos os usuários.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    /**
     * Armazena um novo usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        return response()->json($usuario, 201);
    }

    /**
     * Exibe os detalhes de um usuário específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($usuario);
    }

    /**
     * Atualiza os dados de um usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $request->validate([
            'nome' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'senha' => 'sometimes|string|min:8',
            'role' => 'sometimes|string',
            'documento' => 'nullable|string',
        ]);

        if ($request->has('senha')) {
            $request->merge(['senha' => bcrypt($request->senha)]);
        }

        $usuario->update($request->all());

        return response()->json($usuario);
    }

    /**
     * Remove um usuário.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $usuario->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso']);
    }
}