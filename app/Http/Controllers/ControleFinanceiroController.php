<?php

namespace App\Http\Controllers;

use App\Models\ControleFinanceiro;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ControleFinanceiroController extends Controller
{
    public function index()
    {
        // Obtém todas as parcelas
        $parcelas = ControleFinanceiro::with('cliente')->get();

        // Verifica se alguma parcela vence em 7 dias ou menos e envia notificação
        foreach ($parcelas as $parcela) {
            $dataVencimento = Carbon::parse($parcela->data_vencimento);
            $diasRestantes = $dataVencimento->diffInDays(now());

            if ($diasRestantes <= 7 && $parcela->status_pagamento == 'pendente') {
                // Enviar e-mail de notificação
                $this->enviarNotificacao($parcela->cliente, $parcela);
            }
        }

        return view('controle_financeiro.index', compact('parcelas'));
    }

    public function create()
{
    $clientes = Cliente::all(); // Supondo que você queira vincular a parcela a um cliente
    return view('controle_financeiro.create', compact('clientes'));
}

public function store(Request $request)
{
    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'parcela_numero' => 'required|integer',
        'valor' => 'required|numeric',
        'data_vencimento' => 'required|date',
    ]);

    ControleFinanceiro::create([
        'cliente_id' => $request->cliente_id,
        'parcela_numero' => $request->parcela_numero,
        'valor' => $request->valor,
        'data_vencimento' => $request->data_vencimento,
        'status_pagamento' => 'pendente',
    ]);

    return redirect()->route('controle_financeiro.index')->with('sucesso', 'Parcela adicionada com sucesso.');
}


    public function atualizarStatus(Request $request, $id)
    {
        $parcela = ControleFinanceiro::find($id);
    
        if (!$parcela) {
            return redirect()->back()->with('erro', 'Parcela não encontrada.');
        }
    
        $parcela->status_pagamento = 'pago';
        $parcela->save(); 

        $cliente = $parcela->cliente;
        $dadosEmail = [
            'nome' => $cliente->nome,
            'parcela' => $parcela->parcela_numero,
            'valor' => $parcela->valor,
            'data_pagamento' => now()->format('d/m/Y H:i'),


        ];

        Mail::send('emails.confirmacao_pagamento', $dadosEmail, function($message) use ($cliente) {
            $message->to($cliente->email)
                    ->subject('Confirmação de Pagamento');
        });
    
        return redirect()->back()->with('sucesso', 'Pagamento confirmado com sucesso.');
    }
    

    // Método para enviar e-mail de notificação
    private function enviarNotificacao($cliente, $parcela)
    {
        $assunto = 'Lembrete de Vencimento de Parcela';
        $dados = [
            'nome' => $cliente->nome,
            'parcela' => $parcela->parcela_numero,
            'valor' => $parcela->valor,
            'data_vencimento' => $parcela->data_vencimento,
        ];

        // Enviar e-mail
        Mail::send('emails.vencimento_notificacao', $dados, function($message) use ($cliente, $assunto) {
            $message->to($cliente->email)
                    ->subject($assunto);
        });
    }
}
