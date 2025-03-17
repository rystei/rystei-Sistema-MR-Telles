{{-- resources/views/processos/status.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Gerenciar Status do Processo: {{ $processo->id }}</h2>

    @php
        // Definição completa dos status disponíveis (para o dropdown)
        $allStatuses = [
            'protocolado'                 => 'Protocolado',
            'audiencia_conciliação'       => 'Audiência de Conciliação',
            'acordo'                      => 'Acordo',
            'audiencia_instrucao'         => 'Audiência de Instrução',
            'aguardando_sentenca'         => 'Aguardando Sentença',
            'sentenca'                    => 'Sentença',
            'sentenca_primeiro_grau'      => 'Sentença de Primeiro Grau',
            'recursos'                    => 'Recursos',
            'aguardando_sentenca_tribunal' => 'Aguardando Sentença no Tribunal',
            'decisao_tribunal'            => 'Decisão do Tribunal',
            'encerrado'                   => 'Encerrado'
        ];

        // Normaliza o status atual para comparação
        $normalizedCurrentStatus = strtolower(trim($processo->status_atual));

        // Garante que o histórico seja um array
        $historico = is_array($processo->historico) ? $processo->historico : json_decode($processo->historico, true) ?? [];
    @endphp

    <!-- Formulário de Atualização de Status -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('processos.updateStatus', $processo) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="novo_status">Selecione o novo status</label>
                    <select name="novo_status" id="novo_status" class="form-control" required>
                        @foreach ($allStatuses as $key => $label)
                            <option value="{{ $key }}" {{ $key == $normalizedCurrentStatus ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Atualizar Status</button>
            </form>
        </div>
    </div>

    <!-- Histórico de Atualizações com opção de exclusão -->
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Histórico de Atualizações</h5>
            <ul class="list-group">
                @foreach ($historico as $index => $etapa)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $processo->statusFormatado($etapa['status']) }}</strong>
                            <small class="text-muted"> - {{ $etapa['data'] }}</small>
                        </div>
                        <form action="{{ route('processos.deleteHistorico', ['processo' => $processo->id, 'index' => $index]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esse registro?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<style>
/* ========== ESTILOS GLOBAIS ========== */
.card {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.btn {
    transition: all 0.2s ease;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(to right, #0d6efd, #0b5ed7);
    border: none;
    color: white !important;
}

.btn-danger {
    background: linear-gradient(to right, #dc3545, #c82333);
    border: none;
}

/* ========== FORMULÁRIO ========== */
.form-select, .form-control {
    border: 2px solid #dee2e6;
    transition: border-color 0.3s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

/* ========== LISTA DE HISTÓRICO ========== */
.list-group-item {
    border: none;
    margin-bottom: 0.75rem;
    border-radius: 0.5rem !important;
    background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
    box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.list-group-item:hover {
    transform: translateX(8px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
}

/* ========== STATUS BADGE ========== */
.status-badge {
    border-radius: 6px;
    padding: 0.35rem 1rem;
    font-size: 0.9rem;
    border: 1px solid rgba(13, 110, 253, 0.2);
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    display: inline-block;
}

/* ========== ESTADO VAZIO ========== */
.empty-state {
    opacity: 0.8;
    padding: 2rem 0;
    text-align: center;
}

.empty-state i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #6c757d;
}

/* ========== EFEITOS HOVER ========== */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
}
</style>
@endsection
