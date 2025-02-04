@extends('layouts.app')

@section('title', 'Dashboard do Gerente')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Dashboard do Gerente</h2>
        <p>Bem-vindo, {{ Auth::user()->nome }}!</p>
    </div>

    <!-- Cards de Acesso Rápido -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Viaturas</h5>
                <p class="card-text">Gerencie as viaturas registradas.</p>
                <a href="{{ route('viaturas.index') }}" class="btn btn-primary">
                    <i class="fas fa-car"></i> Acessar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
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

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ordens de Serviço</h5>
                <p class="card-text">Gerencie as ordens de serviço.</p>
                <a href="{{ route('ordens-servico.index') }}" class="btn btn-primary">
                    <i class="fas fa-clipboard-list"></i> Acessar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Relatórios</h5>
                <p class="card-text">Acesse os relatórios gerenciais.</p>
                <a href="/relatorios" class="btn btn-primary">
                    <i class="fas fa-chart-bar"></i> Acessar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas e Gráficos -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Estatísticas Gerais</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <h3>{{ App\Models\Viatura::count() }}</h3>
                            <p>Viaturas</p>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <h3>{{ App\Models\OrdemServico::count() }}</h3>
                            <p>Ordens de Serviço</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3>{{ App\Models\OrdemServico::where('status', 'Em Andamento')->count() }}</h3>
                            <p>Serviços em Andamento</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3>{{ App\Models\OrdemServico::where('status', 'Concluído')->count() }}</h3>
                            <p>Serviços Concluídos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Últimos Pagamentos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Cliente</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Pagamento::with(['viatura.cliente'])->latest()->take(5)->get() as $pagamento)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($pagamento->data)) }}</td>
                                <td>{{ $pagamento->viatura->cliente->nome }}</td>
                                <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                                <th>Status</th>
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
                                    <span class="badge bg-{{ $ordem->status == 'Concluído' ? 'success' : 'warning' }}">
                                        {{ $ordem->status }}
                                    </span>
                                </td>
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