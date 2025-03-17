{{-- resources/views/processos/meus.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Meus Processos</h3>

    <!-- Formulário de Pesquisa pelo Nº do Processo -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('processos.meus') }}" class="row g-3">
                <div class="col-md-10">
                    <label for="numero_processo" class="form-label">Pesquisar por Nº do Processo</label>
                    <input 
                        type="text" 
                        name="numero_processo" 
                        id="numero_processo" 
                        class="form-control" 
                        placeholder="Digite o número do processo" 
                        value="{{ request('numero_processo') }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Pesquisar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Processos do Usuário -->
    <div class="card">
        <div class="card-body">
            @forelse($processos as $processo)
                <div class="mb-3">
                    <h5>Nº Processo: {{ $processo->numero_processo }}</h5>
                    <p>{{ $processo->descricao }}</p>
                    <div class="text-muted">
                        Status: {{ ucfirst($processo->status_atual) }} | 
                        Última atualização: {{ $processo->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <a href="{{ route('processos.meusDetalhes', $processo) }}" class="btn btn-outline-info btn-sm mt-2">
                        <i class="bi bi-eye"></i> Ver Detalhes
                    </a>
                </div>
                <hr>
            @empty
                <div class="alert alert-info">Nenhum processo encontrado.</div>
            @endforelse
        </div>
    </div>
</div>
<style>
/* Componentes Reutilizáveis */
.btn {
    transition: all 0.2s ease;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.375rem 0.75rem !important;
    border-radius: 0.375rem !important;
}

.btn-primary {
    background: linear-gradient(to right, #0d6efd, #0b5ed7);
    color: white !important;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Cards */
.card {
    border-radius: 0.75rem;
    overflow: hidden;
    border: none;
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08);
}

/* Tabela */
.table-hover tbody tr {
    transition: transform 0.2s, box-shadow 0.2s;
    background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
}

.table-hover tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
}

/* Status */
.status-badge {
    border-radius: 6px;
    padding: 0.35rem 1rem;
    font-size: 0.9rem;
    border: 1px solid rgba(13, 110, 253, 0.2);
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

/* Estados Vazios */
.empty-state {
    opacity: 0.8;
    padding: 2rem 0;
}

/* Campos de Formulário */
.form-control {
    border: 2px solid #dee2e6;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

/* Paginação */
.pagination .page-link {
    border-radius: 0.5rem;
    margin: 0 0.25rem;
    border: none;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(to right, #0d6efd, #0b5ed7);
}
</style>
@endsection
