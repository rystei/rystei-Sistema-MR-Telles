{{-- resources/views/processos/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Gerenciar Processos</h2>

    <!-- Formulário de Pesquisa -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('processos.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label for="cliente" class="form-label">Pesquisar por Cliente</label>
                    <input type="text" name="cliente" id="cliente" class="form-control" placeholder="Nome do Cliente" value="{{ request('cliente') }}">
                </div>
                <div class="col-md-5">
                    <label for="numero_processo" class="form-label">Pesquisar por Nº do Processo</label>
                    <input type="text" name="numero_processo" id="numero_processo" class="form-control" placeholder="Número do Processo" value="{{ request('numero_processo') }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulário de Criação -->
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

    <!-- Lista de Processos -->
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
                    @forelse($processos as $processo)
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
                    @empty
                        <tr>
                          <td colspan="5" class="text-center">Nenhum processo encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Links de Paginação -->
            <div class="d-flex justify-content-center">
                {{ $processos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
