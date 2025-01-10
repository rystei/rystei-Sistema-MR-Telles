@component('mail::message')
# Lembrete de Vencimento de Parcela

Olá, {{ $parcela->cliente->nome }}.

Essa é uma notificação para lembrá-lo de que sua parcela está próxima do vencimento.

- **Número da Parcela**: {{ $parcela->parcela_numero }}
- **Valor**: R$ {{ number_format($parcela->valor, 2, ',', '.') }}
- **Data de Vencimento**: {{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}

Por favor, realize o pagamento até a data indicada.

@component('mail::button', ['url' => ''])
Acessar Sistema
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
