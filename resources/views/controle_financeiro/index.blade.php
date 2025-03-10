@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Controle Financeiro</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
                    @foreach($cliente->controleFinanceiro->groupBy('lote') as $lote => $parcelas)
                        <div class="lote-group mb-4 border-bottom pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>
                                        Criado: 
                                        @if(strlen($lote) >= 14)
                                            {{ \Carbon\Carbon::createFromFormat('YmdHis', $lote)->format('d/m/Y H:i') }}
                                        @else
                                            #{{ $lote }} (Criado em: {{ $parcelas->first()->created_at->format('d/m/Y') }})
                                        @endif
                                    </h6>
                                </div>
                                <span class="badge bg-primary">
                                    {{ count($parcelas) }} Parcela(s)
                                </span>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Parcela</th>
                                        <th>Descrição</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parcelas->sortBy('data_vencimento') as $parcela)
                                        <tr>
                                            <td>{{ $parcela->parcela_numero }}</td>
                                            <td>{{ $parcela->descricao }}</td>
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
                                                        {{ $parcela->data_pagamento->format('d/m/Y') }}
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
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection
