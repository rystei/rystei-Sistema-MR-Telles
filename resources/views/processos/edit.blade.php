<!-- resources/views/processos/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Editar Processo</div>
        <div class="card-body">
            <form action="{{ route('processos.update', $processo) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label>Cliente</label>
                        <select name="client_id" class="form-select" required>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ $processo->client_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Número do Processo</label>
                        <input type="text" name="numero_processo" class="form-control" 
                               value="{{ $processo->numero_processo }}" required>
                    </div>
                    
                    <div class="col-12">
                        <label>Descrição</label>
                        <textarea name="descricao" class="form-control" rows="3" required>{{ $processo->descricao }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        </div>
    </div>
</div>
@endsection