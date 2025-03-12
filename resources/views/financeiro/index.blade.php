@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gerador de PIX</h1>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Configuração do PIX
        </div>
        <div class="card-body">
            <form id="pixForm" action="{{ route('financeiro.calculate') }}" method="POST">
                @csrf
                
                <!-- Campos ocultos para valores numéricos -->
                <input type="hidden" name="total_amount" id="realTotalAmount" value="{{ old('total_amount') }}">
                <input type="hidden" name="percentage" id="realPercentage" value="{{ old('percentage') }}">
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Valor Total (R$)</label>
                            <input type="text" id="displayTotalAmount" class="form-control money-mask" 
                                   placeholder="R$ 0,00" 
                                   value="{{ old('total_amount') ? number_format(old('total_amount'), 2, ',', '.') : '' }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Percentual (%)</label>
                            <input type="text" id="displayPercentage" class="form-control percent-mask" 
                                   placeholder="0%"
                                   value="{{ old('percentage') ? number_format(old('percentage'), 2, ',', '.') : '' }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Parcelas</label>
                            <input type="number" name="installments" class="form-control" 
                                   min="1" required 
                                   value="{{ old('installments') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-qrcode"></i> Gerar PIX
                </button>
            </form>

            @isset($qrCode)
                <div class="mt-5 text-center">
                    <div class="alert alert-success">
                        Valor do PIX: <strong>R$ {{ number_format($chargeAmount, 2, ',', '.') }}</strong>
                    </div>
                    
                    <div class="qr-code-container bg-light p-3 rounded">
                        {!! $qrCode !!}
                    </div>
                    
                    <div class="mt-3 text-muted">
                        <small>
                            Escaneie com seu banco ou:
                            <div class="mt-2 d-flex gap-2 align-items-center justify-content-center">
                                <button class="btn btn-outline-primary btn-sm" onclick="copyPixPayload()">
                                    <i class="fas fa-copy me-2"></i>Copiar Código
                                </button>
                                <code id="pixPayload" class="text-break">{{ $pixPayload ?? '' }}</code>
                            </div>
                            <div id="copyFeedback" class="text-success small mt-1" style="display: none;">
                                Código copiado com sucesso!
                            </div>
                        </small>
                    </div>
                </div>
            @endisset
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mt-4">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        // Máscara Monetária Corrigida
        $('#displayTotalAmount').on('input', function(e) {
    let rawValue = this.value.replace(/\D/g, '');
    
    // Tratamento de exclusão
    if (e.inputType?.includes('delete')) {
        if (rawValue === '') {
            this.value = '';
            return;
        }
    }

    // Garante pelo menos 3 dígitos para centavos
    rawValue = rawValue.padStart(3, '0');

    // Separação inteiros/centavos
    const inteiros = rawValue.slice(0, -2) || '0';
    const centavos = rawValue.slice(-2);

    // Formatação de milhares
    const formattedInteiros = inteiros
        .split('').reverse().join('')
        .replace(/(\d{3})(?=\d)/g, '$1.')
        .split('').reverse().join('')
        .replace(/^\./, '')
        .replace(/^0+(?=\d)/, ''); // Remove zeros à esquerda

    // Atualiza o campo
    this.value = formattedInteiros === '' 
        ? `R$ 0,${centavos}`
        : `R$ ${formattedInteiros},${centavos}`;
});

        // Máscara Percentual (mantida)
        $('#displayPercentage').mask('##0,00%', {
            reverse: false,
            translation: {
                '#': { pattern: /\d/ }
            }
        });

        // Submit handler (mantido)
        $('#pixForm').submit(function(e){
            const totalValue = parseFloat(
                $('#displayTotalAmount').val()
                    .replace('R$ ', '')
                    .replace(/\./g, '')
                    .replace(',', '.')
            ).toFixed(2);
            
            $('#realTotalAmount').val(totalValue);

            const percentValue = parseFloat(
                $('#displayPercentage').val()
                    .replace('%', '')
                    .replace(',', '.')
            ).toFixed(2);
            
            $('#realPercentage').val(percentValue);
        });

        // Preenchimento após erros (mantido)
        @if($errors->any())
            $('#displayTotalAmount').val(
                new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format({{ old('total_amount', 0) }})
            );

            $('#displayPercentage').val(
                new Intl.NumberFormat('pt-BR', {
                    style: 'percent',
                    minimumFractionDigits: 2
                }).format({{ old('percentage', 0) }}/100)
            );
        @endif
    });

    // Função de cópia (mantida)
    function copyPixPayload() {
        /* ... */
    }
</script>
@endsection

<style>
    .qr-code-container svg {
        width: 300px !important;
        height: 300px !important;
        border: 1px solid #ddd;
        padding: 10px;
    }
    
    .money-mask, .percent-mask {
        text-align: right;
        padding-right: 40px;
    }

    #pixPayload {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 4px;
        max-width: 600px;
        word-break: break-all;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white !important;
    }
</style>
@endsection