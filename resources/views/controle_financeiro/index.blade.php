@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Controle Financeiro</h1>



    <!-- Filtro de Pesquisa -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('controle_financeiro.search') }}" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Pesquisar cliente por nome ou e-mail" 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
                </div>
            </form>
        </div>
    </div>

    <a href="{{ route('controle_financeiro.create') }}" class="btn btn-primary mb-4">Adicionar Nova Parcela</a>

    @foreach ($clientes as $cliente)
        @if($cliente->controleFinanceiro->count() > 0)
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ $cliente->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Total:</strong> {{ $cliente->controleFinanceiro->count() }}
                        </div>
                        <div class="col-md-4">
                            <strong>Pagas:</strong> {{ $cliente->controleFinanceiro->where('status_pagamento', 'pago')->count() }}
                        </div>
                        <div class="col-md-4">
                            <strong>Pendentes:</strong> {{ $cliente->controleFinanceiro->where('status_pagamento', 'pendente')->count() }}
                        </div>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Parcela</th>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cliente->controleFinanceiro->sortBy('data_vencimento') as $parcela)
                                <tr>
                                    <td>{{ $parcela->parcela_numero }}/{{ $cliente->controleFinanceiro->count() }}</td>
                                    <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                                    <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $parcela->status_pagamento == 'pago' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($parcela->status_pagamento) }}
                                        </span>
                                    </td>
                                    <td class="d-flex gap-2">
                                        @if ($parcela->status_pagamento == 'pendente')
                                            <form action="{{ route('controle_financeiro.atualizarStatus', $parcela->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">Pagar</button>
                                            </form>
                                        @else
                                            <small class="text-muted">
                                                {{ $parcela->data_pagamento->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                        
                                        <form action="{{ route('controle_financeiro.destroy', $parcela->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Tem certeza que deseja excluir esta parcela?')">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection