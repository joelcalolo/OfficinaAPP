@extends('layouts.app')

@section('title', 'Dashboard do Técnico')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Dashboard do Técnico</h2>
        <p>Bem-vindo, {{ Auth::user()->nome }}!</p>
    </div>

    <!-- Card de Acesso Rápido -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ordens de Serviço</h5>
                <p class="card-text">Gerencie as ordens de serviço atribuídas a você.</p>
                <a href="{{ route('ordens-servico.index') }}" class="btn btn-primary">
                    <i class="fas fa-clipboard-list"></i> Acessar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Ordens de Serviço Ativas -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Minhas Ordens de Serviço Ativas</h5>
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
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\OrdemServico::with(['viatura', 'viatura.cliente'])
                                ->where('mecanico_id', Auth::id())
                                ->where('status', '!=', 'Concluído')
                                ->latest()
                                ->get() as $ordem)
                            <tr>
                                <td>{{ $ordem->id }}</td>
                                <td>{{ $ordem->viatura->marca }} {{ $ordem->viatura->modelo }}</td>
                                <td>{{ $ordem->viatura->cliente->nome }}</td>
                                <td>{{ date('d/m/Y', strtotime($ordem->data_servico)) }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ $ordem->status }}</span>
                                </td>
                                <td>
                                    <a href="/ordens-servico/{{ $ordem->id }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/ordens-servico/{{ $ordem->id }}/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
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