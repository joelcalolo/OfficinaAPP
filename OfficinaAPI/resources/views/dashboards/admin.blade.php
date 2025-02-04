@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Dashboard Administrativo</h2>
        <p>Bem-vindo, {{ Auth::user()->nome }}!</p>
    </div>

    <!-- Cards de Acesso Rápido -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Gestão de Usuários</h5>
                <p class="card-text">Gerencie todos os usuários do sistema.</p>
                <a href="{{ route('usuarios.index') }}" class="btn btn-primary">
                    <i class="fas fa-users"></i> Acessar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Viaturas</h5>
                <p class="card-text">Gerencie todas as viaturas registradas.</p>
                <a href="{{ route('viaturas.index') }}" class="btn btn-primary">
                    <i class="fas fa-car"></i> Acessar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Serviços</h5>
                <p class="card-text">Gerencie os serviços oferecidos.</p>
                <a href="{{ route('servicos.index') }}" class="btn btn-primary">
                    <i class="fas fa-wrench"></i> Acessar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Estatísticas Gerais</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3>{{ App\Models\Usuario::count() }}</h3>
                            <p>Usuários</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3>{{ App\Models\Viatura::count() }}</h3>
                            <p>Viaturas</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3>{{ App\Models\OrdemServico::count() }}</h3>
                            <p>Ordens de Serviço</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3>{{ App\Models\Servico::count() }}</h3>
                            <p>Serviços</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection