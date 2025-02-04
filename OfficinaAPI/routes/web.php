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
use App\Http\Controllers\PagamentoController;

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes
    Route::resource('usuarios', UsuarioController::class);
    
    // Vehicle Routes
    Route::resource('viaturas', ViaturaController::class);
    Route::get('/minhas-viaturas', [ViaturaController::class, 'minhasViaturas'])->name('viaturas.minhas');
    
    // Service Routes
    Route::resource('servicos', ServicoController::class);
    
    // Service Order Routes
    Route::resource('ordens-servico', OrdemServicoController::class);
    
    // Report Routes
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    
    // Profile Routes
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::delete('/perfil', [PerfilController::class, 'destroy'])->name('perfil.destroy');
    
    // Payment Routes
    Route::resource('pagamentos', PagamentoController::class);
    Route::post('/pagamentos/confirmar', [PagamentoController::class, 'confirmarPagamento'])->name('pagamentos.confirmar');
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});