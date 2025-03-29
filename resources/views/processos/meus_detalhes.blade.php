@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="text-center text-primary mb-4">Detalhes do Processo Nº {{ $processo->numero_processo }}</h3>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p class="mb-3"><strong>Descrição:</strong> {{ $processo->descricao }}</p>
            <p>
                <strong>Status Atual:</strong>
                <span class="badge bg-success text-white">{{ $processo->statusFormatado() }}</span>
            </p>
        </div>
    </div>

    <!-- Histórico de Atualizações (Timeline) -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Histórico de Atualizações</h5>
        </div>
        <div class="card-body">
            @if ($processo->historicos->count() > 0)
                <div class="timeline">
                    @foreach ($processo->historicos->sortBy('created_at') as $registro)
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="mb-1">{{ $processo->statusFormatado($registro->status_atual) }}</h5>
                                <small class="text-muted">{{ $registro->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    Nenhum histórico encontrado.
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection

<style>
/* ========== ESTILOS GERAIS ========== */
.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
}

h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

/* ========== CARDS ========== */
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

.card-body {
    padding: 1.5rem;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: none;
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
}

/* ========== BADGES ========== */
.badge {
    font-size: 1rem;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    background: linear-gradient(135deg, #198754, #157347);
    color: white !important;
}

/* ========== TIMELINE ========== */
.timeline {
    position: relative;
    margin-top: 20px;
    padding-left: 40px;
    border-left: 3px solid #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 20px;
    transition: all 0.3s ease;
}

.timeline-item:hover {
    transform: translateX(10px);
}

.timeline-item::before {
    content: "";
    position: absolute;
    left: -33px;
    top: 0;
    width: 20px;
    height: 20px;
    background: #0d6efd;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.timeline-content:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.timeline-content h5 {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
    color: #333;
}

.timeline-content small {
    font-size: 0.9rem;
    color: #6c757d;
}

/* ========== ALERTAS ========== */
.alert {
    border-radius: 8px;
    padding: 1rem;
    font-size: 1rem;
    background: linear-gradient(135deg, #0dcaf0, #0da5f0);
    color: white;
    border: none;
}

/* ========== RESPONSIVIDADE ========== */
@media (max-width: 768px) {
    .container {
        padding: 1.5rem;
    }

    h3 {
        font-size: 1.75rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    .timeline {
        padding-left: 30px;
    }

    .timeline-item::before {
        left: -28px;
    }
}
</style>
