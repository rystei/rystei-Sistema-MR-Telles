@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Calculo de PIX</h3>
        <div class="badge bg-primary rounded-pill px-3 py-2">
            <i class="fas fa-qrcode me-2"></i>Pagamento via PIX
        </div>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configuração do PIX</h5>
        </div>
        
        <div class="card-body p-4">
            <form id="pixForm" action="{{ route('financeiro.calculate') }}" method="POST">
                @csrf
                
                <input type="hidden" name="total_amount" id="realTotalAmount" value="{{ old('total_amount') }}">
                <input type="hidden" name="percentage" id="realPercentage" value="{{ old('percentage') }}">
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-coins text-primary fs-4"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Valor Total (R$)</label>
                                <input type="text" id="displayTotalAmount" 
                                       class="form-control money-mask @error('total_amount') is-invalid @enderror" 
                                       placeholder="R$ 0,00"
                                       value="{{ old('total_amount') ? number_format(old('total_amount'), 2, ',', '.') : '' }}">
                                @error('total_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-percent text-primary fs-4"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Percentual (%)</label>
                                <input type="text" id="displayPercentage" 
                                       class="form-control percent-mask @error('percentage') is-invalid @enderror" 
                                       placeholder="0%"
                                       value="{{ old('percentage') ? number_format(old('percentage'), 2, ',', '.') : '' }}">
                                @error('percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-list-ol text-primary fs-4"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Parcelas</label>
                                <input type="number" name="installments" 
                                       class="form-control @error('installments') is-invalid @enderror" 
                                       min="1" required 
                                       value="{{ old('installments') }}">
                                @error('installments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg py-3">
                        <i class="fas fa-qrcode me-2"></i>Gerar PIX
                    </button>
                </div>
            </form>

            @isset($qrCode)
                <div class="payment-card mt-5 p-4 rounded-3 border border-2 border-primary-subtle">
                    <div class="row align-items-center g-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-info-circle text-primary fs-4"></i>
                                <div>
                                    <h5 class="fw-bold mb-2">Instruções de Pagamento</h5>
                                    <ol class="text-muted small">
                                        <li>Abra o aplicativo do seu banco</li>
                                        <li>Selecione a opção Pagar com PIX</li>
                                        <li>Escaneie o QR Code ou cole o código</li>
                                        <li>Confira os dados e finalize o pagamento</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="qr-code-container bg-white p-3 rounded-3 shadow-sm">
                                {!! $qrCode !!}
                                <div class="mt-3 small text-muted">
                                    <i class="fas fa-expand-arrows-alt me-2"></i>
                                    Toque para ampliar
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-outline-primary" onclick="copyPixPayload()">
                                <i class="fas fa-copy me-2"></i>Copiar Código
                            </button>
                            <code id="pixPayload" class="text-break small">
                                {{ $pixPayload ?? '' }}
                            </code>
                        </div>
                        <div id="copyFeedback" class="text-success small mt-2" style="display: none;">
                            Código copiado com sucesso!
                        </div>
                    </div>
                </div>
            @endisset

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        // Máscara Monetária 
        $('#displayTotalAmount').on('input', function(e) {
            let rawValue = this.value.replace(/\D/g, '');
            
            if (e.inputType?.includes('delete')) {
                if (rawValue === '') {
                    this.value = '';
                    return;
                }
            }

            rawValue = rawValue.padStart(3, '0');
            const inteiros = rawValue.slice(0, -2) || '0';
            const centavos = rawValue.slice(-2);

            const formattedInteiros = inteiros
                .split('').reverse().join('')
                .replace(/(\d{3})(?=\d)/g, '$1.')
                .split('').reverse().join('')
                .replace(/^\./, '')
                .replace(/^0+(?=\d)/, '');

            this.value = formattedInteiros === '' 
                ? `R$ 0,${centavos}`
                : `R$ ${formattedInteiros},${centavos}`;
        });

        $('#displayPercentage').mask('##0,00%', {
            reverse: false,
            translation: {
                '#': { pattern: /\d/ }
            }
        });

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

    function copyPixPayload() {
        const payload = document.getElementById('pixPayload');
        navigator.clipboard.writeText(payload.innerText).then(() => {
            document.getElementById('copyFeedback').style.display = 'block';
            setTimeout(() => {
                document.getElementById('copyFeedback').style.display = 'none';
            }, 3000);
        });
    }
</script>
@endsection

<style>
    .payment-card {
        transition: transform 0.2s, box-shadow 0.2s;
        background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
    }
    
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
    }

    .qr-code-container svg {
        width: 200px;
        height: 200px;
        transition: transform 0.3s ease;
    }

    .qr-code-container:hover svg {
        transform: scale(1.05);
    }

    .btn-primary {
        background: linear-gradient(to right, #0d6efd, #0b5ed7);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn-primary:after {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(45deg);
        transition: all 0.5s;
    }

    .btn-primary:hover:after {
        left: 50%;
    }

    .form-control {
        border: 2px solid #dee2e6;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }

    .invalid-feedback {
        font-size: 0.875em;
        color: #dc3545;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.25);
    }
</style>