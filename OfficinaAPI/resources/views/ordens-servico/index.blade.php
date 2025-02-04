@extends('layouts.app')

@section('title', 'Ordens de Serviço')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Ordens de Serviço</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrdemModal">
        <i class="fas fa-plus"></i> Nova Ordem de Serviço
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Viatura</th>
                        <th>Cliente</th>
                        <th>Mecânico</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordensServico as $ordem)
                    <tr>
                        <td>{{ $ordem->id }}</td>
                        <td>{{ $ordem->viatura->marca }} {{ $ordem->viatura->modelo }}</td>
                        <td>{{ $ordem->viatura->cliente->nome }}</td>
                        <td>{{ $ordem->mecanico->nome }}</td>
                        <td>{{ date('d/m/Y', strtotime($ordem->data_servico)) }}</td>
                        <td>
                            <span class="badge bg-{{ $ordem->status == 'Concluído' ? 'success' : 'warning' }}">
                                {{ $ordem->status }}
                            </span>
                        </td>
                        <td>R$ {{ number_format($ordem->total, 2, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewOrdemModal{{ $ordem->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOrdemModal{{ $ordem->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteOrdemModal{{ $ordem->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Adicionar Ordem de Serviço -->
<div class="modal fade" id="addOrdemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Ordem de Serviço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('ordens-servico.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="viatura_id" class="form-label">Viatura</label>
                            <select class="form-select" id="viatura_id" name="viatura_id" required>
                                @foreach($viaturas as $viatura)
                                    <option value="{{ $viatura->id }}">
                                        {{ $viatura->marca }} {{ $viatura->modelo }} - {{ $viatura->cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mecanico_id" class="form-label">Mecânico</label>
                            <select class="form-select" id="mecanico_id" name="mecanico_id" required>
                                @foreach($mecanicos as $mecanico)
                                    <option value="{{ $mecanico->id }}">{{ $mecanico->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Serviços</label>
                        <div id="servicos-container">
                            <div class="row mb-2 servico-item">
                                <div class="col-md-6">
                                    <select class="form-select" name="servicos[0][servico_id]" required>
                                        @foreach($servicos as $servico)
                                            <option value="{{ $servico->id }}" data-preco="{{ $servico->preco }}">
                                                {{ $servico->nome }} - R$ {{ number_format($servico->preco, 2, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="servicos[0][quantidade]" placeholder="Quantidade" value="1" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remover-servico">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="adicionar-servico">
                            <i class="fas fa-plus"></i> Adicionar Serviço
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($ordensServico as $ordem)
<!-- Modal Visualizar Ordem de Serviço -->
<div class="modal fade" id="viewOrdemModal{{ $ordem->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Ordem de Serviço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Informações da Viatura</h6>
                        <p><strong>Marca/Modelo:</strong> {{ $ordem->viatura->marca }} {{ $ordem->viatura->modelo }}</p>
                        <p><strong>Cliente:</strong> {{ $ordem->viatura->cliente->nome }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informações do Serviço</h6>
                        <p><strong>Mecânico:</strong> {{ $ordem->mecanico->nome }}</p>
                        <p><strong>Data:</strong> {{ date('d/m/Y', strtotime($ordem->data_servico)) }}</p>
                        <p><strong>Status:</strong> {{ $ordem->status }}</p>
                    </div>
                </div>

                <h6>Serviços Realizados</h6>
                <table class="table table-sm">
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
        </div>
    </div>
</div>

<!-- Modal Editar Ordem de Serviço -->
<div class="modal fade" id="editOrdemModal{{ $ordem->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Ordem de Serviço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('ordens-servico.update', $ordem->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Em Análise" {{ $ordem->status == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                                <option value="Em Andamento" {{ $ordem->status == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="Concluído" {{ $ordem->status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mecanico_id" class="form-label">Mecânico</label>
                            <select class="form-select" id="mecanico_id" name="mecanico_id" required>
                                @foreach($mecanicos as $mecanico)
                                    <option value="{{ $mecanico->id }}" {{ $ordem->mecanico_id == $mecanico->id ? 'selected' : '' }}>
                                        {{ $mecanico->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Excluir Ordem de Serviço -->
<div class="modal fade" id="deleteOrdemModal{{ $ordem->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir esta ordem de serviço?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('ordens-servico.destroy', $ordem->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
let servicoCount = 1;

document.getElementById('adicionar-servico').addEventListener('click', function() {
    const container = document.getElementById('servicos-container');
    const novoServico = document.createElement('div');
    novoServico.className = 'row mb-2 servico-item';
    novoServico.innerHTML = `
        <div class="col-md-6">
            <select class="form-select" name="servicos[${servicoCount}][servico_id]" required>
                @foreach($servicos as $servico)
                    <option value="{{ $servico->id }}" data-preco="{{ $servico->preco }}">
                        {{ $servico->nome }} - R$ {{ number_format($servico->preco, 2, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="servicos[${servicoCount}][quantidade]" placeholder="Quantidade" value="1" min="1" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remover-servico">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(novoServico);
    servicoCount++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remover-servico') || e.target.closest('.remover-servico')) {
        const servicoItem = e.target.closest('.servico-item');
        if (document.querySelectorAll('.servico-item').length > 1) {
            servicoItem.remove();
        }
    }
});
</script>
@endpush
@endsection