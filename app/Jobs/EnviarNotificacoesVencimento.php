<?php

namespace App\Jobs;

use App\Models\Parcela;
use App\Mail\ParcelaVencimentoNotificacao;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarNotificacoesVencimento implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Busca todas as parcelas que vencem em 7 dias ou menos e que ainda estão pendentes
        $parcelas = Parcela::where('status_pagamento', 'pendente')
            ->whereDate('data_vencimento', '<=', now()->addDays(7))
            ->get();

        foreach ($parcelas as $parcela) {
            // Envia um e-mail para o cliente
            Mail::to($parcela->cliente->email)
                ->send(new ParcelaVencimentoNotificacao($parcela));
        }
    }
}
