@extends('layouts.app')

@section('title', 'Minhas Viaturas')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Minhas Viaturas</h2>
        <p>Bem-vindo, {{ Auth::user()->nome }}!</p>
    </div>

    <!-- Lista de Viaturas do Cliente -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Minhas Viaturas em Manutenção</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Marca/Modelo</th>
                                <th>Estado</th>
                                <th>Última Atualização</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(Auth::user()->veiculos as $viatura)
                            <tr>
                                <td>{{ $viatura->marca }} {{ $viatura->modelo }}</td>
                                <td>
                                    <span class="badge bg-{{ $viatura->estado == 'Concluído' ? 'success' : 'warning' }}">
                                        {{ $viatura->estado }}
                                    </span>
                                </td>
                                <td>{{ $viatura->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detalhesModal{{ $viatura->id }}">
                                        <i class="fas fa-eye"></i> Detalhes
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhuma viatura encontrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Serviços -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Histórico de Serviços</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Viatura</th>
                                <th>Serviço</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $hasServices = false; @endphp
                            @foreach(Auth::user()->veiculos as $viatura)
                                @foreach($viatura->ordensServico as $ordem)
                                    @foreach($ordem->servicos as $servico)
                                        @php $hasServices = true; @endphp
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($ordem->data_servico)) }}</td>
                                            <td>{{ $viatura->marca }} {{ $viatura->modelo }}</td>
                                            <td>{{ $servico->servico->nome }}</td>
                                            <td>R$ {{ number_format($servico->preco_unitario * $servico->quantidade, 2, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ordem->status == 'Concluído' ? 'success' : 'warning' }}">
                                                    {{ $ordem->status ?? $viatura->estado }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                            @if(!$hasServices)
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum serviço encontrado.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais de Detalhes -->
@foreach(Auth::user()->veiculos as $viatura)
<div class="modal fade" id="detalhesModal{{ $viatura->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Viatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Informações da Viatura -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Informações do Veículo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Marca:</strong> {{ $viatura->marca }}</p>
                                <p><strong>Modelo:</strong> {{ $viatura->modelo }}</p>
                                <p><strong>Cor:</strong> {{ $viatura->cor }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Estado:</strong> 
                                    <span class="badge bg-{{ $viatura->estado == 'Concluído' ? 'success' : 'warning' }}">
                                        {{ $viatura->estado }}
                                    </span>
                                </p>
                                <p><strong>Tipo de Avaria:</strong> {{ $viatura->tipo_avaria }}</p>
                                <p><strong>Código de Validação:</strong> {{ $viatura->codigo_validacao }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ordens de Serviço -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Ordens de Serviço</h6>
                    </div>
                    <div class="card-body">
                        @if($viatura->ordensServico->count() > 0)
                            @foreach($viatura->ordensServico as $ordem)
                                <div class="border-bottom mb-3 pb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>Ordem #{{ $ordem->id }} - {{ date('d/m/Y', strtotime($ordem->data_servico)) }}</h6>
                                        <span class="badge bg-{{ $ordem->status == 'Concluído' ? 'success' : 'warning' }}">
                                            {{ $ordem->status }}
                                        </span>
                                    </div>
                                    <p><strong>Mecânico:</strong> {{ $ordem->mecanico->nome }}</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Serviço</th>
                                                <th>Quantidade</th>
                                                <th>Preço Unit.</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ordem->servicos as $servico)
                                                <tr>
                                                    <td>{{ $servico->servico->nome }}</td>
                                                    <td>{{ $servico->quantidade }}</td>
                                                    <td>R$ {{ number_format($servico->preco_unitario, 2, ',', '.') }}</td>
                                                    <td>R$ {{ number_format($servico->quantidade * $servico->preco_unitario, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total:</th>
                                                <th>R$ {{ number_format($ordem->total, 2, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center mb-0">Nenhuma ordem de serviço encontrada.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection