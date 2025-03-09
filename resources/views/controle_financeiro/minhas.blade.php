@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Minhas Parcelas</h3>

    <!-- Filtro de Pesquisa (mantido se necessário) -->
    
    <div class="card">
        <div class="card-body">
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
                </div>
            @empty
                <div class="alert alert-info">Nenhuma parcela encontrada.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection