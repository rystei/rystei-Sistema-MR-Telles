{{-- resources/views/processos/meus_detalhes.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detalhes do Processo Nº {{ $processo->numero_processo }}</h3>
    
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Descrição:</strong> {{ $processo->descricao }}</p>
            <p><strong>Status Atual:</strong> {{ ucfirst($processo->status_atual) }}</p>
        </div>
    </div>

    <!-- Histórico de Atualizações (Timeline) -->
    <div class="card">
        <div class="card-header">
            <h5 class="text-decoration-underline">Histórico de Atualizações</h5>
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
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h5 class="mb-1">
                                    {{ $processo->statusFormatado($etapa['status']) }}
                                </h5>
                                <small class="text-muted">
                                    {{ $etapa['data'] }}
                                </small>
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

@push('styles')
<style>
/* Estilos para a timeline vertical */
.timeline {
    position: relative;
    margin: 20px 0;
    padding: 0;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #ddd;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 60px; /* Espaço para o ícone */
}
.timeline-item:last-child {
    margin-bottom: 0;
}
.timeline-icon {
    position: absolute;
    left: 0;
    width: 40px;
    height: 40px;
    background: #fff;
    border: 2px solid #ddd;
    border-radius: 50%;
    text-align: center;
    line-height: 36px;
    color: #999;
    font-size: 18px;
}
.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 6px;
}
.timeline-content h5 {
    margin-top: 0;
}
</style>
@endpush
