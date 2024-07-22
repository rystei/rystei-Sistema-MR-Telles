<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Recursos Financeiros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    @include('layouts.navbar')
</head>
<body>
    <div class="container mt-5">
        <h2>Valor do processo</h2>
        <form id="financialForm">
            <div class="form-group">
                <label for="total_amount">Valor Total Recebido:</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" required>
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
        <div id="result" class="mt-3" style="display: none;">
            <h4>Resultado:</h4>
            <p>Valor a ser Cobrado: <span id="charge_amount"></span></p>
            <p>Valor por Parcela: <span id="installment_amount"></span></p>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#financialForm').on('submit', function(e) {
                e.preventDefault();

                var data = {
                    total_amount: $('#total_amount').val(),
                    percentage: $('#percentage').val(),
                    installments: $('#installments').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    type: 'POST',
                    url: '/financeiro/calculate',
                    data: data,
                    success: function(response) {
                        $('#charge_amount').text(response.charge_amount.toFixed(2));
                        $('#installment_amount').text(response.installment_amount.toFixed(2));
                        $('#result').show();
                    },
                    error: function(error) {
                        console.error('Erro:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
