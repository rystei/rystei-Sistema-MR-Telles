@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Gerenciar Status do Processo: {{ $processo->numero_processo }}</h2>

    @php
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
    @endphp

    <!-- Formulário de Atualização de Status -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('processos.updateStatus', $processo) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="novo_status" class="form-label">Selecione o novo status</label>
                    <select name="novo_status" id="novo_status" class="form-control" required>
                        @foreach ($allStatuses as $key => $label)
                            <option value="{{ $key }}" {{ $key == $normalizedCurrentStatus ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Novo campo para selecionar a data do status -->
                <div class="mb-3">
                    <label for="data_status" class="form-label">Data do Status</label>
                    <input type="datetime-local" name="data_status" id="data_status" class="form-control" value="{{ old('data_status', now()->format('Y-m-d\TH:i')) }}" required>
                    <small class="text-muted">Selecione a data e hora em que a decisão ocorreu.</small>
                </div>
                <button type="submit" class="btn btn-primary mt-3">
                    Atualizar Status
                </button>
            </form>
        </div>
    </div>

 <!-- Histórico de Atualizações -->
 <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Histórico de Atualizações</h5>
            <ul class="list-group">
                @foreach ($processo->historicos->sortBy('created_at') as $registro)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $processo->statusFormatado($registro->status_atual) }}</strong>
                            <small class="text-muted"> - {{ $registro->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <form action="{{ route('processos.deleteHistorico', ['processo' => $processo, 'historico' => $registro]) }}" method="POST">
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
.container {
    max-width: 900px;
    margin: 0 auto;
}

h2 {
    font-size: 2rem;
    font-weight: 700;
}

/* Card */
.card {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: #ffffff;
    margin-bottom: 1.5rem;
}

.card:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
}

/* Botões */
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
    color: #fff !important;
}

.btn-danger {
    background: linear-gradient(to right, #dc3545, #c82333);
    border: none;
}

/* Formulários */
.form-label {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control, .form-select {
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
}

/* Lista de Histórico */
.list-group-item {
    border: none;
    margin-bottom: 0.75rem;
    border-radius: 0.5rem;
    background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
    box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.list-group-item:hover {
    transform: translateX(8px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
}
</style>
@endsection
