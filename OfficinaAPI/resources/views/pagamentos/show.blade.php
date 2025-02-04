@extends('layouts.app')

@section('title', 'Detalhes do Pagamento')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Detalhes do Pagamento</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Informações do Pagamento</h4>
                    <table class="table">
                        <tr>
                            <th>Código de Referência:</th>
                            <td>{{ $pagamento->codigo_referencia }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ $pagamento->status == 'pago' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pagamento->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Valor:</th>
                            <td>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Data:</th>
                            <td>{{ $pagamento->data->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Informações da Ordem de Serviço</h4>
                    <table class="table">
                        <tr>
                            <th>Ordem de Serviço:</th>
                            <td>#{{ $pagamento->ordemServico->id }}</td>
                        </tr>
                        <tr>
                            <th>Cliente:</th>
                            <td>{{ $pagamento->ordemServico->viatura->cliente->nome }}</td>
                        </tr>
                        <tr>
                            <th>Viatura:</th>
                            <td>{{ $pagamento->ordemServico->viatura->marca }} {{ $pagamento->ordemServico->viatura->modelo }}</td>
                        </tr>
                        <tr>
                            <th>Mecânico:</th>
                            <td>{{ $pagamento->ordemServico->mecanico->nome }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h4 class="mt-4">Serviços Incluídos</h4>
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
                        @foreach($pagamento->ordemServico->servicos as $servico)
                        <tr>
                            <td>{{ $servico->servico->nome }}</td>
                            <td>{{ $servico->quantidade }}</td>
                            <td>R$ {{ number_format($servico->preco_unitario, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($servico->quantidade * $servico->preco_unitario, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('pagamentos.index') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
</div>
@endsection