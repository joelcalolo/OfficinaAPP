<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViaturaController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\ServicoPrestadoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Rotas para USUARIOS
Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index']); // Listar todos os usuários
    Route::post('/', [UsuarioController::class, 'store']); // Criar um novo usuário
    Route::get('/{id}', [UsuarioController::class, 'show']); // Exibir detalhes de um usuário
    Route::put('/{id}', [UsuarioController::class, 'update']); // Atualizar um usuário
    Route::delete('/{id}', [UsuarioController::class, 'destroy']); // Deletar um usuário
});



Route::post('/registrar', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

//Rotas para VIATURAS
Route::prefix('viaturas')->group(function () {
    Route::get('/', [ViaturaController::class, 'index']); // Listar todas as viaturas
    Route::post('/', [ViaturaController::class, 'store']); // Criar uma nova viatura
    Route::get('/{id}', [ViaturaController::class, 'show']); // Obter detalhes de uma viatura
    Route::put('/{id}', [ViaturaController::class, 'update']); // Atualizar uma viatura
    Route::delete('/{id}', [ViaturaController::class, 'destroy']); // Excluir uma viatura
    Route::get('/{id}/qrcode', [ViaturaController::class, 'gerarQrCode']); // Gerar QR Code para uma viatura
});

//Rotas para SERVIÇOS
Route::prefix('servicos')->group(function () {
    Route::get('/', [ServicoController::class, 'index']); // Listar todos os serviços
    Route::post('/', [ServicoController::class, 'store']); // Criar um novo serviço
    Route::get('/{id}', [ServicoController::class, 'show']); // Obter detalhes de um serviço
    Route::put('/{id}', [ServicoController::class, 'update']); // Atualizar um serviço
    Route::delete('/{id}', [ServicoController::class, 'destroy']); // Excluir um serviço
});

//Rotas para PAGAMENTOS
Route::prefix('pagamentos')->group(function () {
    Route::get('/', [PagamentoController::class, 'index']); // Listar todos os pagamentos
    Route::post('/', [PagamentoController::class, 'store']); // Criar um novo pagamento
    Route::get('/{id}', [PagamentoController::class, 'show']); // Obter detalhes de um pagamento
    Route::put('/{id}', [PagamentoController::class, 'update']); // Atualizar um pagamento
    Route::delete('/{id}', [PagamentoController::class, 'destroy']); // Excluir um pagamento
});

//Rotas para Relatorio
Route::prefix('relatorios')->group(function () {
    Route::get('/viaturas', [RelatorioController::class, 'relatorioViaturas']); // Relatório de viaturas
    Route::get('/servicos', [RelatorioController::class, 'relatorioServicos']); // Relatório de serviços
});

//Rotas de servicos prestados
Route::post('/servicos-prestados', [ServicoPrestadoController::class, 'store']);

