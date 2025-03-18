@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Gerenciar Processos</h3>
        <div class="badge bg-primary rounded-pill px-3 py-2">
            <i class="fas fa-balance-scale me-2"></i>Jurídico
        </div>
    </div>

    <!-- Formulário de Pesquisa -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('processos.index') }}" class="row g-4">
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-user text-primary fs-5"></i>
                        <div class="w-100">
                            <label class="text-muted small mb-1">Pesquisar por Cliente</label>
                            <input type="text" name="cliente" id="cliente" 
                                   class="form-control" 
                                   placeholder="Nome do Cliente" 
                                   value="{{ request('cliente') }}">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-hashtag text-primary fs-5"></i>
                        <div class="w-100">
                            <label class="text-muted small mb-1">Pesquisar por Nº do Processo</label>
                            <input type="text" name="numero_processo" id="numero_processo" 
                                   class="form-control" 
                                   placeholder="Número do Processo" 
                                   value="{{ request('numero_processo') }}">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-search me-2 fs-5"></i>Pesquisar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulário de Criação -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body p-4">
            <form action="{{ route('processos.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-users text-primary fs-5"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Cliente</label>
                                <select name="user_id" class="form-select" required>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-file-alt text-primary fs-5"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Número do Processo</label>
                                <input type="text" name="numero_processo" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-align-left text-primary fs-5"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Descrição</label>
                                <input type="text" name="descricao" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-plus-circle me-2 fs-5"></i>Criar Processo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Processos -->
    <div class="card border-0 shadow-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-primary">Nº Processo</th>
                            <th class="text-primary">Cliente</th>
                            <th class="text-primary">Descrição</th>
                            <th class="text-primary">Status</th>
                            <th class="text-primary text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($processos as $processo)
                            <tr class="payment-card">
                                <td class="fw-bold">{{ $processo->numero_processo }}</td>
                                <td>{{ $processo->cliente->name }}</td>
                                <td>{{ Str::limit($processo->descricao, 40) }}</td>
                                <td>
                                    <span class="status-badge">
                                    {{ $processo->statusFormatado() }}                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('processos.editStatus', $processo) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-diagram-project me-1 fs-5"></i>Status
                                        </a>
                                        <form action="{{ route('processos.destroy', $processo) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1 fs-5"></i>Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h5 class="fw-bold text-muted">Nenhum processo encontrado</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-4">
                {{ $processos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* Estilos dos Botões Uniformizados para <a> e <button> */
a.btn, button.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.375rem 0.75rem !important;
    font-size: 0.875rem !important;
    border-radius: 0.375rem !important;
    line-height: 1;
    vertical-align: middle;
    height: 2.5rem; /* Altura fixa para garantir o mesmo tamanho */
}

.btn {
    transition: all 0.2s ease;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(to right, #0d6efd, #0b5ed7);
    color: white !important;
}

.btn-danger {
    background: linear-gradient(to right, #dc3545, #c82333);
    color: white !important;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Estilos Específicos da Página */
.payment-card {
    transition: transform 0.2s, box-shadow 0.2s;
    background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
}

.payment-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
}

.status-badge {
    border-radius: 6px;
    padding: 0.35rem 1rem;
    font-size: 0.9rem;
    display: inline-block;
    border: 1px solid rgba(13, 110, 253, 0.2);
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.empty-state {
    opacity: 0.8;
}

/* Formulários e Cards */
.form-control {
    border: 2px solid #dee2e6;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

.card {
    border-radius: 0.75rem;
    overflow: hidden;
}

/* Paginação */
.pagination .page-link {
    border-radius: 0.5rem;
    margin: 0 0.25rem;
    border: none;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(to right, #0d6efd, #0b5ed7);
    border-color: transparent;
}
</style>
