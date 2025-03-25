@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Gerenciador de PIX</h3>
        <div class="badge bg-primary rounded-pill px-3 py-2">
            <i class="fas fa-qrcode me-2"></i>Pagamento via PIX
        </div>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Novo PIX</h5>
        </div>
        
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="pixForm" action="{{ route('financeiro.calculate') }}" method="POST">
                @csrf
                <input type="hidden" name="total_amount" id="realTotalAmount" value="{{ old('total_amount') }}">
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-user text-primary fs-4"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Nome do Cliente</label>
                                <input type="text" name="client_name" 
                                       class="form-control @error('client_name') is-invalid @enderror" 
                                       required
                                       value="{{ old('client_name') }}">
                                @error('client_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-coins text-primary fs-4"></i>
                            <div class="w-100">
                                <label class="text-muted small mb-1">Valor (R$)</label>
                                <input type="text" id="displayTotalAmount" 
                                       class="form-control money-mask @error('total_amount') is-invalid @enderror" 
                                       placeholder="R$ 0,00"
                                       value="{{ old('total_amount') ? 'R$ ' . number_format(old('total_amount'), 2, ',', '.') : '' }}">
                                @error('total_amount')
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
                                <h5 class="fw-bold mb-2">Pagamento PIX para {{ $pixData->client_name }}</h5>
                                <p class="text-muted">Valor: R$ {{ number_format($pixData->total_amount, 2, ',', '.') }}</p>
                                <ol class="text-muted small">
                                    <li>Abra o aplicativo do seu banco</li>
                                    <li>Selecione a opção Pagar com PIX</li>
                                    <li>Escaneie o QR Code ou copie o código</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <div class="qr-code-container bg-white p-3 rounded-3 shadow-sm" style="max-width: 250px; margin: 0 auto;">
                            {!! $qrCode !!}
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary" onclick="copyPixPayload()">
                                <i class="fas fa-copy me-2"></i>Copiar Código
                            </button>
                            <code id="pixPayload" class="d-none">{{ $pixPayload }}</code>
                            <div id="copyFeedback" class="text-success small mt-2" style="display: none;">
                                Código copiado com sucesso!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endisset

            <div class="mt-5">
                <h4 class="fw-bold border-bottom pb-2 mb-3">Histórico de PIX Gerados</h4>
                
                <div class="list-group">
                    @forelse($generatedPix as $pix)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $pix->client_name }}</h6>
                                <small class="text-muted">Valor: R$ {{ number_format($pix->total_amount, 2, ',', '.') }}</small>
                                <small class="text-muted ms-2">{{ $pix->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <form action="{{ route('financeiro.pix.delete', $pix->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este PIX?')">
                                    <i class="fas fa-trash me-1"></i>Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">Nenhum PIX gerado ainda</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
        // Máscara Monetária Corrigida
        $('#displayTotalAmount').on('input', function(e) {
            let rawValue = this.value.replace(/\D/g, '');
            
            // Mantém a posição do cursor
            const cursorPosition = this.selectionStart;
            
            // Tratamento de zeros à esquerda
            if (rawValue.length > 2) {
                rawValue = rawValue.replace(/^0+/, '') || '0';
                rawValue = rawValue.padStart(3, '0');
            } else {
                rawValue = rawValue.padStart(3, '0');
            }

            // Separa inteiros e centavos
            const inteiros = rawValue.slice(0, -2);
            const centavos = rawValue.slice(-2);

            // Formatação visual
            let formattedInteiros;
            if (inteiros === '' || inteiros === '0') {
                formattedInteiros = '0';
            } else {
                formattedInteiros = inteiros.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            this.value = `R$ ${formattedInteiros},${centavos}`;
            
            // Atualiza o valor numérico real
            const numericValue = parseFloat(`${inteiros}.${centavos}`).toFixed(2);
            $('#realTotalAmount').val(numericValue);
            
            // Mantém a posição do cursor
            this.setSelectionRange(cursorPosition, cursorPosition);
        });

        // Preenchimento inicial em caso de erros
        @if($errors->any() && old('total_amount'))
            $('#displayTotalAmount').trigger('input');
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
        width: 180px;
        height: 180px;
        transition: transform 0.3s ease;
    }

    .qr-code-container:hover svg {
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .qr-code-container svg {
            width: 150px;
            height: 150px;
        }
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

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }

    .invalid-feedback {
        font-size: 0.875em;
        color: #dc3545;
    }
</style>