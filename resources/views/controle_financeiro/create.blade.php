@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Adicionar Parcelas</h1>

    <form action="{{ route('controle_financeiro.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="user_id" class="form-control" required>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Número de Parcelas</label>
            <input type="number" name="total_parcelas" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Valor de Cada Parcela (R$)</label>
            <input type="text" name="valor" class="form-control" pattern="^\d+(\,\d{1,2})?$" title="Ex: 100 ou 100,50" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Escolha o tipo de vencimento:</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="date" name="data_vencimento" class="form-control" placeholder="Data específica">
                </div>
                <div class="col-md-6">
                    <input type="number" name="dia_fixo" class="form-control" min="1" max="31" placeholder="Dia fixo (ex: 20)">
                </div>
            </div>
            <small class="form-text text-muted">Preencha apenas um dos campos</small>
        </div>

        <button type="submit" class="btn btn-primary">Criar Parcelas</button>
    </form>
</div>
@endsection