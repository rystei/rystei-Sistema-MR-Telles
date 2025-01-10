<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificação de Vencimento</title>
</head>
<body>
    <p>Olá, {{ $nome }}!</p>

    <p>Esta é uma notificação de que a sua parcela número {{ $parcela }} no valor de R$ {{ number_format($valor, 2, ',', '.') }} está com o vencimento próximo.</p>

    <p>Data de vencimento: {{ \Carbon\Carbon::parse($data_vencimento)->format('d/m/Y') }}</p>

    <p>Por favor, efetue o pagamento o quanto antes para evitar problemas.</p>

    <p>Obrigado!</p>
</body>
</html>
