@extends('layouts.app')

<style>
    .container {
        padding: 2rem 0;
        max-width: 1200px;
    }

    h3 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 2rem;
        font-size: 1.75rem;
        letter-spacing: -0.5px;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        background: #ffffff;
        overflow: hidden;
    }

    .card-body {
        padding: 2rem;
    }

    .parcela-card {
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        border: 1px solid #f1f3f5;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        padding: 1.5rem;
        background: #fff;
        position: relative;
    }

    .parcela-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08);
        border-color: rgba(13, 110, 253, 0.15);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
    }

    .status-badge.pago {
        background: linear-gradient(135deg, #198754 0%, #157347 100%);
        color: white !important;
        box-shadow: 0 2px 6px rgba(25, 135, 84, 0.15);
    }

    .status-badge.pendente {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #2c3e50 !important;
        box-shadow: 0 2px 6px rgba(255, 193, 7, 0.15);
    }

    .parcela-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .parcela-value {
        color: #34495e;
        font-weight: 500;
        font-size: 1rem;
    }

    @media (max-width: 992px) {
        .parcela-card .row > div {
            margin-bottom: 1rem;
        }

        .status-badge {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .parcela-card {
            padding: 1.25rem;
        }

        h3 {
            font-size: 1.5rem;
        }
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
    }

    .empty-state .alert {
        border: none;
        box-shadow: none;
    }
</style>

@section('content')
<div class="container">
    <h3>Minhas Parcelas</h3>    
    <div class="card">
        <div class="card-body">
            @forelse($parcelas as $parcela)
                <div class="parcela-card">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <span class="parcela-label">Parcela</span>
                            <div class="parcela-value">#{{ $parcela->parcela_numero }}</div>
                        </div>
                        <div class="col-md-4">
                            <span class="parcela-label">Descrição</span>
                            <div class="parcela-value">{{ $parcela->descricao }}</div>
                        </div>
                        <div class="col-md-2">
                            <span class="parcela-label">Valor</span>
                            <div class="parcela-value text-primary">R$ {{ number_format($parcela->valor, 2, ',', '.') }}</div>
                        </div>
                        <div class="col-md-2">
                            <span class="parcela-label">Vencimento</span>
                            <div class="parcela-value">{{ $parcela->data_vencimento->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-2">
                            <span class="status-badge {{ $parcela->status_pagamento == 'pago' ? 'pago' : 'pendente' }}">
                                {{ ucfirst($parcela->status_pagamento) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="alert alert-info">
                        Nenhuma parcela encontrada
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection