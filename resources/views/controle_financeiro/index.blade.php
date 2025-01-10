@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Controle Financeiro</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Botão para criar nova parcela -->
    <a href="{{ route('controle_financeiro.create') }}" class="btn btn-primary mb-4">Adicionar Nova Parcela</a>

    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Parcela</th>
                <th scope="col">Valor</th>
                <th scope="col">Data de Vencimento</th>
                <th scope="col">Status</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parcelas as $parcela)
                <tr>
                    <td>{{ $parcela->cliente->nome }}</td>
                    <td>{{ $parcela->parcela_numero }}</td>
                    <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($parcela->status_pagamento) }}</td>
                    <td>
                        @if($parcela->status_pagamento == 'pendente')
                            <form method="POST" action="{{ route('controle_financeiro.update_status', $parcela->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">Marcar como Pago</button>
                            </form>
                        @else
                            <span class="badge bg-success">Pago</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
