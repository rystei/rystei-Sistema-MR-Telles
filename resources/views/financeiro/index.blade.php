<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciar Recursos Financeiros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Gerenciar Recursos Financeiros</h2>
        <form id="financeForm">
            @csrf
            <div class="form-group">
                <label for="amount">Valor (R$):</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="discount_percentage">Percentual de Desconto (%):</label>
                <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required>
            </div>
            <div class="form-group">
                <label for="installments">Número de Parcelas:</label>
                <input type="number" class="form-control" id="installments" name="installments" required>
            </div>
            <button type="submit" class="btn btn-primary">Calcular</button>
        </form>
        <div id="result" class="mt-4"></div>
    </div>
    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#financeForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/financeiro/calculate',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#result').html(`
                        <p>Valor Final: R$ ${response.final_amount.toFixed(2)}</p>
                        <p>Valor da Parcela: R$ ${response.installment_amount.toFixed(2)}</p>
                        <div>
                            <h4>QR Code Pix:</h4>
                            <img src="data:image/png;base64,${response.qr_code}" alt="QR Code Pix">
                        </div>
                    `);
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
