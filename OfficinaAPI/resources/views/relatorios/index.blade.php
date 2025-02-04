@extends('layouts.app')

@section('title', 'Relatórios')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Relatórios</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="relatorioForm" class="mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="viatura_id" class="form-label">Viatura</label>
                            <select class="form-select" id="viatura_id" name="viatura_id">
                                <option value="">Todas</option>
                                @foreach($viaturas as $viatura)
                                    <option value="{{ $viatura->id }}">{{ $viatura->marca }} {{ $viatura->modelo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select class="form-select" id="cliente_id" name="cliente_id">
                                <option value="">Todos</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="tecnico_id" class="form-label">Técnico</label>
                            <select class="form-select" id="tecnico_id" name="tecnico_id">
                                <option value="">Todos</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tipo_relatorio" class="form-label">Tipo de Relatório</label>
                            <select class="form-select" id="tipo_relatorio" name="tipo_relatorio" required>
                                <option value="servicos">Serviços</option>
                                <option value="faturamento">Faturamento</option>
                                <option value="clientes">Clientes</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Gerar Relatório
                            </button>
                            <button type="button" class="btn btn-success" id="exportarPDF">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </button>
                        </div>
                    </div>
                </form>

                <div id="resultadoRelatorio">
                    <!-- Os resultados do relatório serão carregados aqui via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('relatorioForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Aqui você implementaria a lógica para carregar o relatório via AJAX
    const formData = new FormData(this);
    
    fetch('/api/relatorios/gerar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Atualizar a div com os resultados
        document.getElementById('resultadoRelatorio').innerHTML = data.html;
    })
    .catch(error => console.error('Erro:', error));
});

document.getElementById('exportarPDF').addEventListener('click', function() {
    // Implementar a lógica de exportação para PDF
    const formData = new FormData(document.getElementById('relatorioForm'));
    
    fetch('/api/relatorios/exportar-pdf', {
        method: 'POST',
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'relatorio.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    })
    .catch(error => console.error('Erro:', error));
});
</script>
@endpush