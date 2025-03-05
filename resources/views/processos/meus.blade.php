<!-- resources/views/processos/meus.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Meus Processos</h3>
    
    <div class="card">
        <div class="card-body">
            @forelse($processos as $processo)
            <div class="mb-3">
                <h5>{{ $processo->numero_processo }}</h5>
                <p>{{ $processo->descricao }}</p>
                <div class="text-muted">
                    Status: {{ ucfirst($processo->status_atual) }} | 
                    Última atualização: {{ $processo->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <hr>
            @empty
            <div class="alert alert-info">Nenhum processo encontrado.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection