@extends('layouts.app')

@section('title', 'Detalhes da Ordem de Serviço')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Detalhes da Ordem de Serviço #{{ $ordem->id }}</h2>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Informações da Viatura</h4>
                <table class="table table-sm">
                    <tr>
                        <th>Marca/Modelo:</th>
                        <td>{{ $ordem->viatura->marca }} {{ $ordem->viatura->modelo }}</td>
                    </tr>
                    <tr>
                        <th>Cliente:</th>
                        <td>{{ $ordem->viatura->cliente->nome }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            <span class="badge bg-{{ $ordem->status == 'Concluído' ? 'success' : 'warning' }}">
                                {{ $ordem->status }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h4>Informações do Serviço</h4>
                <table class="table table-sm">
                    <tr>
                        <th>Data:</th>
                        <td>{{ date('d/m/Y', strtotime($ordem->data_servico)) }}</td>
                    </tr>
                    <tr>
                        <th>Mecânico:</th>
                        <td>{{ $ordem->mecanico->nome }}</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>R$ {{ number_format($ordem->total, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($ordem->pagamento)
        <div class="card mb-4 {{ $ordem->pagamento->status == 'pago' ? 'border-success' : 'border-warning' }}">
            <div class="card-header {{ $ordem->pagamento->status == 'pago' ? 'bg-success text-white' : 'bg-warning' }}">
                <h5 class="mb-0">Informações do Pagamento</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Código de Referência:</strong> {{ $ordem->pagamento->codigo_referencia }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $ordem->pagamento->status == 'pago' ? 'success' : 'warning' }}">
                                {{ ucfirst($ordem->pagamento->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Valor:</strong> R$ {{ number_format($ordem->pagamento->valor, 2, ',', '.') }}</p>
                        <p><strong>Data:</strong> {{ $ordem->pagamento->data->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($ordem->pagamento->status == 'pendente' && Auth::user()->role == 'cliente')
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> Para efetuar o pagamento, utilize o código de referência acima.
                </div>
                @endif
            </div>
        </div>
        @elseif(Auth::user()->role == 'cliente' && $ordem->status == 'Concluído')
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pagamento</h5>
            </div>
            <div class="card-body">
                <p>Esta ordem de serviço está pronta para pagamento.</p>
                <form action="{{ route('pagamentos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ordem_servico_id" value="{{ $ordem->id }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-credit-card"></i> Gerar Pagamento
                    </button>
                </form>
            </div>
        </div>
        @endif

        <h4>Serviços Realizados</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Serviço</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
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

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('ordens-servico.index') }}" class="btn btn-secondary me-2">Voltar</a>
            @if(in_array(Auth::user()->role, ['admin', 'secretario', 'gerente', 'tecnico']))
            <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editStatusModal">
                <i class="fas fa-edit"></i> Atualizar Status
            </button>
            @endif
        </div>
    </div>
</div>

@if(in_array(Auth::user()->role, ['admin', 'secretario', 'gerente', 'tecnico']))
<!-- Modal Editar Status -->
<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atualizar Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('ordens-servico.update', $ordem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Em Análise" {{ $ordem->status == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                            <option value="Em Andamento" {{ $ordem->status == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="Concluído" {{ $ordem->status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                        </select>
                    </div>
                    <input type="hidden" name="mecanico_id" value="{{ $ordem->mecanico_id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection