@extends('layouts.app')

<style>
    /* ========== ESTILOS GERAIS ========== */
    .container {
        padding: 2rem 0;
        max-width: 800px;
        margin: 0 auto;
    }

    h1 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 2rem;
        font-size: 2.5rem;
        text-align: center;
    }

    /* ========== FORMULÁRIO ========== */
    .form-label {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }

    /* ========== CARTÃO ========== */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        background: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 2rem;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* ========== BOTÃO PRIMÁRIO ========== */
    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        border: none;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }

    /* ========== ALERTAS ========== */
    .alert-danger {
        border-radius: 8px;
        padding: 1rem;
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #ffffff;
        border: none;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .alert-danger li {
        margin-bottom: 0.5rem;
    }

    /* ========== PREVISÃO DE VENCIMENTO ========== */
    #previsaoVencimento {
        font-size: 0.95rem;
        margin-top: 0.75rem;
        font-weight: 500;
    }

    /* ========== RESPONSIVIDADE ========== */
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem;
        }

        h1 {
            font-size: 2rem;
        }

        .card-body {
            padding: 1.25rem;
        }
    }
</style>

@section('content')
<div class="container">
    <h1>Adicionar Parcelas</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('controle_financeiro.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Cliente</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Selecione um cliente</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Número de Parcelas</label>
                        <input type="number" name="total_parcelas" class="form-control" 
                               min="1" max="360" value="{{ old('total_parcelas') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Valor de Cada Parcela (R$)</label>
                        <input type="text" name="valor" class="form-control" 
                               pattern="^\d+([,.]\d{1,2})?$" 
                               title="Ex: 100 ou 100,50" 
                               value="{{ old('valor') }}" 
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Descrição da Parcela</label>
                        <input type="text" name="descricao" class="form-control" 
                               placeholder="Ex: Honorários, Taxa Administrativa" 
                               value="{{ old('descricao') }}" 
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mês de Início (décimo dia útil do mês)</label>
                        <input type="month" name="mes_inicio" class="form-control" 
                               min="{{ date('Y-m') }}" 
                               value="{{ old('mes_inicio', date('Y-m')) }}"
                               required>
                        @error('mes_inicio')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div id="previsaoVencimento" class="mt-2 text-primary fw-bold"></div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i> Criar Parcelas
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Adicione aqui seus scripts, se necessário
</script>
@endpush
@endsection
