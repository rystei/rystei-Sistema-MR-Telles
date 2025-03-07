{{-- resources/views/processos/meus.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Meus Processos</h3>

    <!-- Formulário de Pesquisa pelo Nº do Processo -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('processos.meus') }}" class="row g-3">
                <div class="col-md-10">
                    <label for="numero_processo" class="form-label">Pesquisar por Nº do Processo</label>
                    <input 
                        type="text" 
                        name="numero_processo" 
                        id="numero_processo" 
                        class="form-control" 
                        placeholder="Digite o número do processo" 
                        value="{{ request('numero_processo') }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Pesquisar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Processos do Usuário -->
    <div class="card">
        <div class="card-body">
            @forelse($processos as $processo)
                <div class="mb-3">
                    <h5>Nº Processo: {{ $processo->numero_processo }}</h5>
                    <p>{{ $processo->descricao }}</p>
                    <div class="text-muted">
                        Status: {{ ucfirst($processo->status_atual) }} | 
                        Última atualização: {{ $processo->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <a href="{{ route('processos.meusDetalhes', $processo) }}" class="btn btn-outline-info btn-sm mt-2">
                        <i class="bi bi-eye"></i> Ver Detalhes
                    </a>
                </div>
                <hr>
            @empty
                <div class="alert alert-info">Nenhum processo encontrado.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
