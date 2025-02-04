<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ViaturaController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\DashboardController;

// Redireciona a raiz para a página de login
Route::get('/', function () {
    return redirect('/login');
});

// Rotas de autenticação
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rotas protegidas que requerem autenticação
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::delete('/perfil', [PerfilController::class, 'destroy'])->name('perfil.destroy');

    // Rotas para Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::get('usuarios/{id}/download-documento', [UsuarioController::class, 'downloadDocumento'])
            ->name('usuarios.download-documento');
    });

    // Rotas comuns a múltiplos roles
    Route::middleware(['role:admin,secretario,gerente'])->group(function () {
        Route::resource('viaturas', ViaturaController::class);
        Route::resource('servicos', ServicoController::class);
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::post('/relatorios/gerar', [RelatorioController::class, 'gerarRelatorio'])->name('relatorios.gerar');
        Route::post('/relatorios/exportar-pdf', [RelatorioController::class, 'exportarPDF'])->name('relatorios.exportar-pdf');
    });

    Route::middleware(['role:admin,secretario,gerente,tecnico'])->group(function () {
        Route::resource('ordens-servico', OrdemServicoController::class);
    });

    // Rotas específicas para clientes
    Route::middleware(['role:cliente'])->group(function () {
        Route::get('/minhas-viaturas', [ViaturaController::class, 'minhasViaturas'])->name('viaturas.minhas');
    });
});