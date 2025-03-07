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
@endsection
