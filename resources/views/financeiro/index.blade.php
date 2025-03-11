@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gerador de PIX</h1>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Configuração do PIX
        </div>
        <div class="card-body">
            <form action="{{ route('financeiro.calculate') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Valor Total (R$)</label>
                            <input type="number" name="total_amount" class="form-control" 
                                   step="0.01" required value="{{ old('total_amount') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Percentual (%)</label>
                            <input type="number" name="percentage" class="form-control" 
                                   step="0.01" required value="{{ old('percentage') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Parcelas</label>
                            <input type="number" name="installments" class="form-control" 
                                   min="1" required value="{{ old('installments') }}">
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
                            Escaneie com seu banco ou copie o código:<br>
                            <code class="d-block mt-2">{{ $pixPayload ?? '' }}</code>
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
@endsection

<style>
    .qr-code-container svg {
        width: 300px !important;
        height: 300px !important;
        border: 1px solid #ddd;
        padding: 10px;
    }
</style>