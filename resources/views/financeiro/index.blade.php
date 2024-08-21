<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciar Recursos Financeiros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="stylesheet" href="estilos/processar_pagamento.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Gerenciar Recursos Financeiros</h2>
                <form id="financialForm">
                    @csrf
                    <div class="form-group">
                        <label for="total_amount">Valor Total Recebido:</label>
                        <input type="text" class="form-control" id="total_amount" name="total_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="percentage">Porcentagem de Cobrança:</label>
                        <input type="number" class="form-control" id="percentage" name="percentage" required>
                    </div>
                    <div class="form-group">
                        <label for="installments">Número de Parcelas:</label>
                        <input type="number" class="form-control" id="installments" name="installments" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Calcular</button>
                </form>
                <div id="result" class="mt-4"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">QR Code Pix</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="qrCodeImage" src="" alt="QR Code Pix">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Aplica a máscara no campo de valor total recebido
        $('#total_amount').mask('000.000.000.000.000,00', {reverse: true});

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#financialForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/financeiro/calculate',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#result').html(`
                        <p>Valor Total a Ser Cobrado: R$ ${response.charge_amount}</p>
                        <p>Valor da Parcela: R$ ${response.installment_amount}</p>
                        <button class="btn btn-success" id="showQrCode">Mostrar QR Code</button>
                    `);

                    $('#qrCodeImage').attr('src', 'data:image/png;base64,' + response.qr_code);

                    $('#showQrCode').on('click', function() {
                        $('#qrCodeModal').modal('show');
                    });
                },
                error: function(xhr) {
                    var errorHtml = '<div class="alert alert-danger">Erro ao calcular. Verifique os dados e tente novamente.</div>';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorHtml += '<div>' + value[0] + '</div>';
                        });
                    }
                    $('#result').html(errorHtml);
                }
            });
        });
    });
    </script>
</body>
</html>
