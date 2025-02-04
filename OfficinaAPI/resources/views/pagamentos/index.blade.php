@extends('layouts.app')

@section('title', 'Pagamentos')

@section('content')
<div class="container">
    <h2 class="mb-4">Pagamentos</h2>

    <!-- Formulário de Confirmação de Pagamento -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Confirmar Pagamento</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pagamentos.confirmar') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="codigo_referencia">Código de Referência</label>
                            <input type="text" class="form-control" id="codigo_referencia" name="codigo_referencia" required>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Confirmar Pagamento
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Pagamentos -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Ordem de Serviço</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagamentos as $pagamento)
                        <tr>
                            <td>{{ $pagamento->codigo_referencia }}</td>
                            <td>{{ $pagamento->ordemServico->viatura->cliente->nome }}</td>
                            <td>#{{ $pagamento->ordemServico->id }}</td>
                            <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $pagamento->status == 'pago' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pagamento->status) }}
                                </span>
                            </td>
                            <td>{{ $pagamento->data->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('pagamentos.show', $pagamento->id) }}" class="btn btn-sm btn-info">
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
@endsection