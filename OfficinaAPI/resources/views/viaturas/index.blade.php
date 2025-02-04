@extends('layouts.app')

@section('title', 'Viaturas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Viaturas</h1>
    <a href="/viaturas/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nova Viatura
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Cor</th>
                        <th>Estado</th>
                        <th>Cliente</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viaturas as $viatura)
                    <tr>
                        <td>{{ $viatura->id }}</td>
                        <td>{{ $viatura->marca }}</td>
                        <td>{{ $viatura->modelo }}</td>
                        <td>{{ $viatura->cor }}</td>
                        <td>
                            <span class="badge bg-{{ $viatura->estado == 'Concluído' ? 'success' : 'warning' }}">
                                {{ $viatura->estado }}
                            </span>
                        </td>
                        <td>{{ $viatura->cliente->nome }}</td>
                        <td>
                            <a href="/viaturas/{{ $viatura->id }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/viaturas/{{ $viatura->id }}/edit" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $viatura->id }}">
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

@foreach($viaturas as $viatura)
<!-- Modal de Exclusão -->
<div class="modal fade" id="deleteModal{{ $viatura->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir a viatura {{ $viatura->marca }} {{ $viatura->modelo }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="/viaturas/{{ $viatura->id }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection