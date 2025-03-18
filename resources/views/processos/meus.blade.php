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
                <div class="processo-item mb-3 p-3 border rounded">
                    <h5>Nº Processo: {{ $processo->numero_processo }}</h5>
                    <p>{{ $processo->descricao }}</p>
                    <div class="text-muted">
                        Status: {{ $processo->statusFormatado() }} | 
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
@endsection

<style>
    /* ========== ESTILOS GERAIS ========== */
    .container {
        padding: 2rem 0;
        max-width: 1200px;
    }

    h3 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 2rem;
        font-size: 1.75rem;
    }

    /* ========== ANIMAÇÃO DE HOVER ========== */
    .processo-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: #fff;
        position: relative;
    }

    .processo-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border-color: rgba(13, 110, 253, 0.15);
    }

    /* ========== BOTÕES ========== */
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

    .btn-outline-info {
        border-color: #0dcaf0;
        color: #0dcaf0;
    }

    .btn-outline-info:hover {
        background: #0dcaf0;
        color: white;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* ========== CARDS ========== */
    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        background: #ffffff;
    }

    .card-body {
        padding: 2rem;
    }

    /* ========== FORMULÁRIOS ========== */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    /* ========== RESPONSIVIDADE ========== */
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .processo-item {
            padding: 1.25rem;
        }

        h3 {
            font-size: 1.5rem;
        }
    }

    /* ========== ESTADO VAZIO ========== */
    .alert-info {
        border-radius: 12px;
        background: linear-gradient(135deg, #0dcaf0, #0da5f0);
        color: white;
        border: none;
    }
</style>
