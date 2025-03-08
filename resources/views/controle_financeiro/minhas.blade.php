@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Minhas Parcelas</h3>

    <!-- Formulário de Pesquisa -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('controle_financeiro.minhas') }}" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Pesquisar por número da parcela ou data (dd/mm/aaaa)" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Pesquisar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Parcelas -->
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Total de Parcelas:</strong> {{ $parcelas->count() }}
                </div>
                <div class="col-md-4">
                    <strong>Pagas:</strong> {{ $parcelas->where('status_pagamento', 'pago')->count() }}
                </div>
                <div class="col-md-4">
                    <strong>Pendentes:</strong> {{ $parcelas->where('status_pagamento', 'pendente')->count() }}
                </div>
            </div>

            @forelse($parcelas as $parcela)
                <div class="mb-3 p-3 border rounded">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Parcela:</strong> {{ $parcela->parcela_numero }}
                        </div>
                        <div class="col-md-3">
                            <strong>Valor:</strong> R$ {{ number_format($parcela->valor, 2, ',', '.') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Vencimento:</strong> {{ $parcela->data_vencimento->format('d/m/Y') }}
                        </div>
                        <div class="col-md-3">
                            <span class="badge {{ $parcela->status_pagamento == 'pago' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($parcela->status_pagamento) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($parcela->status_pagamento == 'pago')
                        <div class="mt-2 text-muted">
                            <small>Pago em: {{ $parcela->data_pagamento->format('d/m/Y H:i') }}</small>
                        </div>
                    @endif
                    
                    <div class="mt-2 d-flex gap-2">
                        @if ($parcela->status_pagamento == 'pendente')
                            <form action="{{ route('controle_financeiro.atualizarStatus', $parcela->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle"></i> Marcar como Pago
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('controle_financeiro.destroy', $parcela->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Tem certeza que deseja excluir esta parcela?')">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Nenhuma parcela encontrada.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection