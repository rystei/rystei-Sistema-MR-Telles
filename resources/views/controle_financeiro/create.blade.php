@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Adicionar Parcelas</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('controle_financeiro.store') }}" method="POST">
        @csrf
        <div class="mb-3">
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

        <div class="mb-3">
            <label class="form-label">Número de Parcelas</label>
            <input type="number" name="total_parcelas" class="form-control" 
                   min="1" max="360" value="{{ old('total_parcelas') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Valor de Cada Parcela (R$)</label>
            <input type="text" name="valor" class="form-control" 
                   pattern="^\d+([,.]\d{1,2})?$" 
                   title="Ex: 100 ou 100,50" 
                   value="{{ old('valor') }}" 
                   required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição da Parcela</label>
            <input type="text" name="descricao" class="form-control" 
                placeholder="Ex: Honorários, Taxa Administrativa" 
                value="{{ old('descricao') }}" 
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mês de Início (décimo dia útil do mês)</label>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <label class="form-text">Selecione o mês inicial:</label>
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
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Criar Parcelas</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mesInicioInput = document.querySelector('input[name="mes_inicio"]');
    const previsaoDiv = document.getElementById('previsaoVencimento');

    function calcularDecimoDiaUtil(dataMes) {
        const [ano, mes] = dataMes.split('-');
        let data = new Date(ano, mes - 1, 1);
        let diasUteis = 0;
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0); // Normalizar data atual

        while(diasUteis < 10) {
            data.setDate(data.getDate() + 1);
            if (data.getDay() !== 0 && data.getDay() !== 6) { // 0=Dom, 6=Sáb
                diasUteis++;
            }
        }

        // Criar cópia para comparação
        const dataComparacao = new Date(data);
        dataComparacao.setHours(0, 0, 0, 0);

        // Se data já passou, calcular próximo mês
        if (dataComparacao < hoje) {
            return calcularDecimoDiaUtil(`${ano}-${Number(mes) + 1}`);
        }

        return data;
    }

    function formatarData(data) {
        return new Intl.DateTimeFormat('pt-BR').format(data);
    }

    function atualizarPrevisao() {
        if (mesInicioInput.value) {
            try {
                const dataVencimento = calcularDecimoDiaUtil(mesInicioInput.value);
                previsaoDiv.innerHTML = `Primeiro vencimento: ${formatarData(dataVencimento)}`;
                previsaoDiv.style.color = '#0d6efd';
            } catch (e) {
                previsaoDiv.innerHTML = 'Selecione um mês válido';
                previsaoDiv.style.color = 'red';
            }
        }
    }

    // Event listeners
    mesInicioInput.addEventListener('change', atualizarPrevisao);
    mesInicioInput.addEventListener('input', atualizarPrevisao);
    
    // Atualização inicial
    atualizarPrevisao();
});
</script>
@endpush
@endsection