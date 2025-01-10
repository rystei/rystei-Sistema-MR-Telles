<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Parcela;
use Illuminate\Support\Facades\Mail;

class EnviarNotificacoesVencimento extends Command
{
    protected $signature = 'notificacoes:enviar';
    protected $description = 'Envia notificações para clientes cujas parcelas vencem em 1 semana';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $hoje = now();
        $vencimentoEmUmaSemana = $hoje->addDays(7);

        // Buscar parcelas que vencem em 7 dias
        $parcelas = Parcela::whereDate('data_vencimento', $vencimentoEmUmaSemana)
                            ->where('status_pagamento', 'pendente')
                            ->with('cliente') // Carregar informações do cliente
                            ->get();

        foreach ($parcelas as $parcela) {
            // Enviar notificação por email
            Mail::raw("Prezado {$parcela->cliente->nome}, sua parcela de número {$parcela->parcela_numero} vence em 1 semana. Valor: {$parcela->valor}.", function ($message) use ($parcela) {
                $message->to($parcela->cliente->email)
                        ->subject('Lembrete de Vencimento de Parcela');
            });
        }

        $this->info('Notificações enviadas com sucesso.');
    }
}
