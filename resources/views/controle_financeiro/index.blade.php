@extends('layouts.app')

<style>
    /* ========== ESTILOS GERAIS ========== */
    .container {
        padding: 2rem 0;
    }

    h1 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 2rem;
    }

    /* ========== COMPONENTES DE BOTÃO ========== */
    a.btn, button.btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem !important;
        font-size: 0.875rem !important;
        border-radius: 0.375rem !important;
        line-height: 1;
        vertical-align: middle;
        height: 2.5rem;
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

    .btn-success {
        background: linear-gradient(to right, #198754, #157347);
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* ========== COMPONENTES DE CARD ========== */
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card-header {
        background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    /* ========== ANIMAÇÕES DA TABELA ========== */
    tr {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
        position: relative;
        background-color: inherit;
    }

    tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                    0 2px 4px -2px rgba(0, 0, 0, 0.1);
        z-index: 2;
    }

    td {
        position: relative;
        vertical-align: middle;
        border-color: #f8f9fa;
        background-color: inherit !important;
    }

    td::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border: 1px solid transparent;
        transition: inherit;
        pointer-events: none;
    }

    tr:hover td::after {
        border-color: rgba(13, 110, 253, 0.15);
    }

    /* ========== ELEMENTOS DA TABELA ========== */
    .table th {
        background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
        border-bottom: 2px solid #dee2e6;
        color: #2c3e50;
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

    /* ========== FORMULÁRIOS ========== */
    .form-control {
        border: 2px solid #dee2e6;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }

    /* ========== RESPONSIVIDADE ========== */
    @media (max-width: 768px) {
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    }

    /* ========== ELEMENTOS ESPECÍFICOS ========== */
    .alert-success {
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .lote-group {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .empty-state {
        opacity: 0.8;
        text-align: center;
        padding: 2rem;
    }
</style>

@section('content')
<div class="container">
    <h1 class="mb-4">Controle Financeiro</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtro de Pesquisa -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('controle_financeiro.search') }}" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Pesquisar cliente por nome ou e-mail" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
                </div>
            </form>
        </div>
    </div>

    <a href="{{ route('controle_financeiro.create') }}" class="btn btn-primary mb-4">Adicionar Nova Parcela</a>

    @foreach ($clientes as $cliente)
        @if($cliente->controleFinanceiro->count() > 0)
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ $cliente->name }}</h5>
                </div>
                <div class="card-body">
                    @foreach($cliente->controleFinanceiro->groupBy('lote') as $lote => $parcelas)
                        <div class="lote-group mb-4 border-bottom pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>
                                        Criado: 
                                        @if(strlen($lote) >= 14)
                                            {{ \Carbon\Carbon::createFromFormat('YmdHis', $lote)->format('d/m/Y H:i') }}
                                        @else
                                            #{{ $lote }} (Criado em: {{ $parcelas->first()->created_at->format('d/m/Y') }})
                                        @endif
                                    </h6>
                                </div>
                                <span class="badge bg-primary">
                                    {{ count($parcelas) }} Parcela(s)
                                </span>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Parcela</th>
                                        <th>Descrição</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parcelas->sortBy('data_vencimento') as $parcela)
                                        <tr>
                                            <td>{{ $parcela->parcela_numero }}</td>
                                            <td>{{ $parcela->descricao }}</td>
                                            <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                                            <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $parcela->status_pagamento == 'pago' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($parcela->status_pagamento) }}
                                                </span>
                                            </td>
                                            <td class="d-flex gap-2">
                                                @if ($parcela->status_pagamento == 'pendente')
                                                    <form action="{{ route('controle_financeiro.atualizarStatus', $parcela->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success">Pagar</button>
                                                    </form>
                                                @else
                                                    <small class="text-muted">
                                                        {{ $parcela->data_pagamento->format('d/m/Y') }}
                                                    </small>
                                                @endif

                                                <form action="{{ route('controle_financeiro.destroy', $parcela->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Tem certeza que deseja excluir esta parcela?')">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection
