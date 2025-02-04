@extends('layouts.app')

@section('title', 'Nova Viatura')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Nova Viatura</h2>
    </div>
    <div class="card-body">
        <form action="/viaturas" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cor" class="form-label">Cor</label>
                    <input type="text" class="form-control" id="cor" name="cor" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tipo_avaria" class="form-label">Tipo de Avaria</label>
                    <input type="text" class="form-control" id="tipo_avaria" name="tipo_avaria" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="Em Análise">Em Análise</option>
                        <option value="Em Reparo">Em Reparo</option>
                        <option value="Concluído">Concluído</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cliente_id" class="form-label">Cliente</label>
                    <select class="form-select" id="cliente_id" name="cliente_id" required>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="codigo_validacao" class="form-label">Código de Validação</label>
                <input type="text" class="form-control" id="codigo_validacao" name="codigo_validacao" required>
            </div>
            <div class="d-flex justify-content-end">
                <a href="/viaturas" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection