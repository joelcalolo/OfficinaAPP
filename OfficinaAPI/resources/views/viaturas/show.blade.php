@extends('layouts.app')

@section('title', 'Detalhes da Viatura')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Detalhes da Viatura</h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h3>Informações da Viatura</h3>
                <table class="table">
                    <tr>
                        <th>Marca:</th>
                        <td>{{ $viatura->marca }}</td>
                    </tr>
                    <tr>
                        <th>Modelo:</th>
                        <td>{{ $viatura->modelo }}</td>
                    </tr>
                    <tr>
                        <th>Cor:</th>
                        <td>{{ $viatura->cor }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Avaria:</th>
                        <td>{{ $viatura->tipo_avaria }}</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            <span class="badge bg-{{ $viatura->estado == 'Concluído' ? 'success' : 'warning' }}">
                                {{ $viatura->estado }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Cliente:</th>
                        <td>{{ $viatura->cliente->nome }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h3>QR Code</h3>
                @if($viatura->estado == 'Concluído')
                    <img src="/viaturas/{{ $viatura->id }}/qrcode" alt="QR Code" class="img-fluid">
                @else
                    <div class="alert alert-info">
                        O QR Code estará disponível quando o serviço for concluído.
                    </div>
                @endif
            </div>
        </div>

        <h3 class="mt-4">Serviços Realizados</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Serviço</th>
                        <th>Técnico</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viatura->ordensServico as $ordem)
                        @foreach($ordem->servicos as $servico)
                            <tr>
                                <td>{{ $ordem->data_servico }}</td>
                                <td>{{ $servico->servico->nome }}</td>
                                <td>{{ $ordem->mecanico->nome }}</td>
                                <td>R$ {{ number_format($servico->preco_unitario * $servico->quantidade, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="/viaturas" class="btn btn-secondary me-2">Voltar</a>
            <a href="/viaturas/{{ $viatura->id }}/edit" class="btn btn-warning me-2">Editar</a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                Excluir
            </button>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir esta viatura?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="/viaturas/{{ $viatura->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection