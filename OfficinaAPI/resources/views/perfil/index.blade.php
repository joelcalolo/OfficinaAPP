@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Meu Perfil</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="{{ Auth::user()->nome }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha_atual" class="form-label">Senha Atual</label>
                        <input type="password" class="form-control" id="senha_atual" name="senha_atual">
                        <small class="text-muted">Deixe em branco se não quiser alterar a senha</small>
                    </div>

                    <div class="mb-3">
                        <label for="nova_senha" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="nova_senha" name="nova_senha">
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                    </div>

                    @if(Auth::user()->role == 'cliente')
                    <div class="mb-3">
                        <label for="documento" class="form-label">Documento</label>
                        <input type="text" class="form-control" id="documento" name="documento" value="{{ Auth::user()->documento }}">
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(Auth::user()->role == 'cliente')
        <div class="card mt-4">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-0">Excluir Conta</h3>
            </div>
            <div class="card-body">
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Atenção:</strong> A exclusão da conta é permanente e não pode ser desfeita.
                </p>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="fas fa-trash"></i> Excluir Minha Conta
                </button>
            </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Exclusão de Conta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('perfil.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Atenção:</strong> Esta ação é irreversível!
                            </p>
                            <p>Para confirmar a exclusão da sua conta, digite sua senha atual:</p>
                            <div class="mb-3">
                                <label for="senha_confirmacao" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha_confirmacao" name="senha_confirmacao" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Excluir Conta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->role == 'cliente')
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="mb-0">Minhas Viaturas</h3>
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
                            @foreach(Auth::user()->veiculos as $viatura)
                            <tr>
                                <td>{{ $viatura->marca }} {{ $viatura->modelo }}</td>
                                <td>
                                    <span class="badge bg-{{ $viatura->estado == 'Concluído' ? 'success' : 'warning' }}">
                                        {{ $viatura->estado }}
                                    </span>
                                </td>
                                <td>{{ $viatura->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="/viaturas/{{ $viatura->id }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detalhes
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection