@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h3 class="text-center text-primary mb-4">Detalhes do Processo Nº {{ $processo->numero_processo }}</h3>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p class="mb-3"><strong>Descrição:</strong> {{ $processo->descricao }}</p>
            <p>
                <strong>Status Atual:</strong>
                <span class="badge bg-success text-white">{{ ucfirst($processo->status_atual) }}</span>
            </p>
        </div>
    </div>

    <!-- Histórico de Atualizações (Timeline) -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Histórico de Atualizações</h5>
        </div>
        <div class="card-body">
            @php
                $historico = is_array($processo->historico)
                    ? $processo->historico
                    : json_decode($processo->historico, true) ?? [];
            @endphp

            @if (count($historico) > 0)
                <div class="timeline">
                    @foreach ($historico as $etapa)
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="mb-1">{{ $processo->statusFormatado($etapa['status']) }}</h5>
                                <small class="text-muted">{{ $etapa['data'] }}</small>
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
@endsection

<style>
/* Container */
.container {
    max-width: 900px;
    margin: 0 auto;
}

/* Título */
h3 {
    font-size: 2rem;
    font-weight: 700;
}

/* Cartões (Cards) */
.card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
}
.card-body {
    padding: 1.5rem;
}
.card-header {
    padding: 1rem 1.5rem;
    border-bottom: none;
}

/* Badge do status */
.badge {
    font-size: 1rem;
    padding: 0.5rem 0.75rem;
}

/* Timeline */
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
}
.timeline-icon {
    display: none; /* Escondendo a área do ícone, visto que já temos o marcador via ::before */
}
.timeline-content {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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

/* Alertas */
.alert {
    border-radius: 8px;
    padding: 1rem;
    font-size: 1rem;
}
</style>
