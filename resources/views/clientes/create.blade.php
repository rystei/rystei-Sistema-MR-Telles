@extends('layouts.app')

@section('content')
    <h1>Adicionar Cliente</h1>

    <form method="POST" action="{{ route('clientes.store') }}">
        @csrf
        <div>
            <label>Nome</label>
            <input type="text" name="nome" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Telefone</label>
            <input type="text" name="telefone" required>
        </div>
        <button type="submit">Adicionar Cliente</button>
    </form>
@endsection
