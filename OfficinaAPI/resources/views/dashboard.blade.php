@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Bem-vindo, {{ Auth::user()->nome }}!</h2>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Viaturas</h6>
                        <h2 class="mb-0">{{ App\Models\Viatura::count() }}</h2>
                    </div>
                    <i class="fas fa-car fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Serviços</h6>
                        <h2 class="mb-0">{{ App\Models\Servico::count() }}</h2>
                    </div>
                    <i class="fas fa-wrench fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Ordens de Serviço</h6>
                        <h2 class="mb-0">{{ App\Models\OrdemServico::count() }}</h2>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Clientes</h6>
                        <h2 class="mb-0">{{ App\Models\Usuario::where('role', 'Cliente')->count() }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimas Ordens de Serviço -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Últimas Ordens de Serviço</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Viatura</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\OrdemServico::with(['viatura', 'viatura.cliente'])->latest()->take(5)->get() as $ordem)
                            <tr>
                                <td>{{ $ordem->id }}</td>
                                <td>{{ $ordem->viatura->marca }} {{ $ordem->viatura->modelo }}</td>
                                <td>{{ $ordem->viatura->cliente->nome }}</td>
                                <td>{{ date('d/m/Y', strtotime($ordem->data_servico)) }}</td>
                                <td>R$ {{ number_format($ordem->total, 2, ',', '.') }}</td>
                                <td>
                                    <a href="/ordens-servico/{{ $ordem->id }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection