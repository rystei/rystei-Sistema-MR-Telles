{{-- resources/views/processos/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Gerenciar Processos</h2>

    <!-- Formulário de criação -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('processos.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Cliente</label>
                        <select name="user_id" class="form-select" required>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label>Número do Processo</label>
                        <input type="text" name="numero_processo" class="form-control" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label>Descrição</label>
                        <input type="text" name="descricao" class="form-control" required>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Criar Processo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de processos -->
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nº Processo</th>
                        <th>Cliente</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processos as $processo)
                        <tr>
                            <td>{{ $processo->numero_processo }}</td>
                            <td>{{ $processo->cliente->name }}</td>
                            <td>{{ Str::limit($processo->descricao, 40) }}</td>
                            <td>{{ ucfirst($processo->status_atual) }}</td>
                            <td class="text-center">
                                <a href="{{ route('processos.editStatus', $processo) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-diagram-3"></i> Status
                                </a>
                                <form action="{{ route('processos.destroy', $processo) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esse processo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
