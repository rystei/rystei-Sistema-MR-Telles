<?php

namespace App\Http\Controllers;

use App\Models\ControleFinanceiro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ControleFinanceiroController extends Controller
{
    public function index()
    {
        $parcelas = ControleFinanceiro::with('user')->get();
    
        foreach ($parcelas as $parcela) {
            $dataVencimento = Carbon::parse($parcela->data_vencimento);
            $diasRestantes = $dataVencimento->diffInDays(now());
    
            // Calcula o total de parcelas do mesmo pagamento
            $totalParcelas = ControleFinanceiro::where('user_id', $parcela->user_id)->count();
    
            // Adiciona o total de parcelas à instância da parcela
            $parcela->total_parcelas = $totalParcelas; 
    
            if ($diasRestantes <= 7 && $parcela->status_pagamento == 'pendente') {
                $this->enviarNotificacao($parcela->user, $parcela);
            }
        }
    
        return view('controle_financeiro.index', compact('parcelas'));
    }
    

    public function create()
    {
        $usuarios = User::all(); // Renomeado para $usuarios, mas pode manter $clientes se preferir
        return view('controle_financeiro.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'parcela_numero'  => 'required|integer',
            'valor'           => 'required|numeric',
            'data_vencimento' => 'required|date',
        ]);

        ControleFinanceiro::create([
            'user_id'         => $request->user_id,
            'parcela_numero'  => $request->parcela_numero,
            'valor'           => $request->valor,
            'data_vencimento' => $request->data_vencimento,
            'status_pagamento'=> 'pendente',
        ]);

        return redirect()->route('controle_financeiro.index')
                         ->with('success', 'Parcela adicionada com sucesso.');
    }

    public function atualizarStatus(Request $request, $id)
    {
        $parcela = ControleFinanceiro::find($id);
        if (!$parcela) {
            return redirect()->back()->with('erro', 'Parcela não encontrada.');
        }

        $parcela->status_pagamento = 'pago';
        $parcela->data_pagamento = now();
        $parcela->save();

        $user = $parcela->user;
        $dadosEmail = [
            'nome'          => $user->name,
            'parcela'       => $parcela->parcela_numero,
            'valor'         => $parcela->valor,
            'data_pagamento'=> now()->format('d/m/Y H:i'),
        ];

        //Mail::send('emails.confirmacao_pagamento', $dadosEmail, function($message) use ($user) {
          //  $message->to($user->email)
            //        ->subject('Confirmação de Pagamento');
      //      });

        return redirect()->back()->with('sucesso', 'Pagamento confirmado com sucesso.');
    }

    private function enviarNotificacao($user, $parcela)
    {
        $assunto = 'Lembrete de Vencimento de Parcela';
        $dados = [
            'nome'           => $user->name,
            'parcela'        => $parcela->parcela_numero,
            'valor'          => $parcela->valor,
            'data_vencimento'=> $parcela->data_vencimento,
        ];

        // Mail::send('emails.vencimento_notificacao', $dados, function($message) use ($user, $assunto) {
        //     $message->to($user->email)
        //             ->subject($assunto);
        // });

    }
}

