@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Pagamento de Parcelas</h3>
        <div class="badge bg-primary rounded-pill px-3 py-2">
            <i class="fas fa-calendar-alt me-2"></i>{{ now()->translatedFormat('F Y') }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg">
        <div class="card-body p-4">
            @forelse($parcelas as $parcela)
                <div class="payment-card mb-3 p-4 rounded-3 {{ $parcela->status_pagamento === 'pago' ? 'bg-light' : 'border border-2 border-primary-subtle' }}">
                    <div class="row align-items-center g-4">
                        <!-- Coluna de Detalhes -->
                        <div class="col-md-5">
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge-container">
                                    <span class="badge {{ $parcela->status_pagamento === 'pago' ? 'bg-success' : 'bg-primary' }} rounded-pill px-3 py-2">
                                        <i class="fas {{ $parcela->status_pagamento === 'pago' ? 'fa-check' : 'fa-rotate' }} me-2"></i>
                                        {{ ucfirst($parcela->status_pagamento) }}
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Parcela #{{ $parcela->parcela_numero }}</h5>
                                    <small class="text-muted">Vencimento: {{ $parcela->data_vencimento->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna de Valor -->
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-coins text-primary fs-4"></i>
                                <div>
                                    <div class="text-muted small">Valor Total</div>
                                    <div class="h5 mb-0 fw-bold text-primary">
                                        R$ {{ number_format($parcela->valor, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna de Ações -->
                        <div class="col-md-4">
                            @if($parcela->status_pagamento === 'pendente')
                                <form action="{{ route('gerar-pix', $parcela->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-2">
                                        <i class="fas fa-qrcode me-2"></i>Gerar Pagamento
                                    </button>
                                </form>
                            @else
                                <div class="paid-badge text-center p-3 rounded-3 bg-success text-white">
                                    <i class="fas fa-check-double fa-2x mb-2"></i>
                                    <div class="small">Pagamento realizado em<br>
                                        {{ $parcela->data_pagamento->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    @if(isset($qrCode) && $parcela->valor == $valorParcela && $parcela->status_pagamento === 'pendente')
                        <div class="mt-4 pt-3 border-top">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Instruções de Pagamento</h6>
                                    <ol class="text-muted small">
                                        <li>Abra o aplicativo do seu banco</li>
                                        <li>Selecione a opção Pagar com PIX</li>
                                        <li>Escaneie o QR Code ou cole o código</li>
                                        <li>Confira os dados e finalize o pagamento</li>
                                    </ol>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="qr-code-container bg-white p-3 rounded-3 shadow-sm">
                                        {!! $qrCode !!}
                                        <div class="mt-3 small text-muted">
                                            <i class="fas fa-expand-arrows-alt me-2"></i>
                                            Toque para ampliar
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-4x text-success opacity-75"></i>
                    </div>
                    <h5 class="fw-bold text-muted mb-2">Todas as parcelas estão em dia!</h5>
                    <p class="text-muted small">Nenhum pagamento pendente para este mês</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

<style>
    .payment-card {
        transition: transform 0.2s, box-shadow 0.2s;
        background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
    }
    
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
    }

    .qr-code-container svg {
        width: 200px;
        height: 200px;
        transition: transform 0.3s ease;
    }

    .qr-code-container:hover svg {
        transform: scale(1.05);
    }

    .empty-state {
        background: url('data:image/svg+xml;utf8,<svg ...>...</svg>') no-repeat center;
        background-size: 150px;
        opacity: 0.8;
    }

    .btn-primary {
        background: linear-gradient(to right, #0d6efd, #0b5ed7);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn-primary:after {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(45deg);
        transition: all 0.5s;
    }

    .btn-primary:hover:after {
        left: 50%;
    }
</style>