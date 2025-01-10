@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Adicionar Nova Parcela</h1>
    <form action="{{ route('controle_financeiro.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="parcela_numero" class="form-label">Número da Parcela</label>
            <input type="number" name="parcela_numero" id="parcela_numero" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <input type="text" name="valor" id="valor" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
            <input type="date" name="data_vencimento" id="data_vencimento" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection
