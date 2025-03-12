@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pagamento de Parcelas deste Mês</h3>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @forelse($parcelas as $parcela)
                <div class="mb-3 p-3 border rounded">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <strong>Parcela #{{ $parcela->parcela_numero }}</strong>
                        </div>
                        <div class="col-md-3">
                            <strong>Valor:</strong> R$ {{ number_format($parcela->valor, 2, ',', '.') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Vence em:</strong> {{ $parcela->data_vencimento->format('d/m/Y') }}
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('gerar-pix', $parcela->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-qrcode"></i> Gerar PIX
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    @if(isset($qrCode) && $parcela->valor == $valorParcela)
                        <div class="mt-3 text-center">
                            <div class="alert alert-info">
                                Escaneie o QR Code abaixo para pagamento
                            </div>
                            <div class="qr-code-container bg-light p-3 rounded">
                                {!! $qrCode !!}
                            </div>
                            <small class="text-muted mt-2 d-block">
                                Valor: R$ {{ number_format($parcela->valor, 2, ',', '.') }}
                            </small>
                        </div>
                    @endif
                </div>
            @empty
                <div class="alert alert-info">Nenhuma parcela para pagamento este mês.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

<style>
    .qr-code-container svg {
        width: 250px;
        height: 250px;
        margin: 0 auto;
        display: block;
    }
    .btn-primary {
        width: 100%;
    }
</style>