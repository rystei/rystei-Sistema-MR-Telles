<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Pagamento</title>
</head>
<body>
    <h1>Confirmação de Pagamento</h1>
    <p>Olá, {{ $nome }}!</p>
    <p>Recebemos o pagamento da sua parcela número <strong>{{ $parcela }}</strong> no valor de <strong>R$ {{ number_format($valor, 2, ',', '.') }}</strong>.</p>
    <p>Data do pagamento: {{ $data_pagamento }}</p>
    <p>Obrigado!</p>
</body>
</html>
