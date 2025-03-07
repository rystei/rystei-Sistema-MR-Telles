<?php

// app/Http\Controllers/ProcessoController.php
namespace App\Http\Controllers;

use App\Models\Processo;
use App\Models\User;
use Illuminate\Http\Request;

class ProcessoController extends Controller
{
    // Listar todos os processos (para admin)
    public function index()
    {
        $processos = Processo::with('cliente')->get();
        $clientes = User::all(); // Lista de usuários para seleção
        return view('processos.index', compact('processos', 'clientes'));
    }

public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'numero_processo' => 'required|unique:processos',
        'descricao' => 'required|min:5' // Reduza para teste ou corrija o input
    ]);

    try {
        Processo::create([
            'user_id' => $request->user_id,
            'numero_processo' => $request->numero_processo,
            'descricao' => $request->descricao,
            'status_atual' => 'protocolado',
            'historico' => json_encode([ // Converta para JSON!
                [
                    'status' => 'protocolado',
                    'data' => now()->toDateTimeString()
                ]
            ])
        ]);

        return redirect()->route('processos.index')->with('success', 'Processo criado!');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->withErrors(['error' => 'Erro ao criar processo: ' . $e->getMessage()]);
    }
}
public function editStatus(Processo $processo)
{
    return view('processos.status', [
        'processo' => $processo,
        'transicoes' => $processo->transicoesPermitidas()
    ]);
}

    // Atualizar Status do Processo
    public function updateStatus(Request $request, Processo $processo)
{
    // Validação: permita somente os status definidos no fluxo
    $allowedStatuses = 'protocolado,audiencia_conciliação,acordo,audiencia_instrucao,aguardando_sentenca,sentenca,sentenca_primeiro_grau,recursos,aguardando_sentenca_tribunal,decisao_tribunal,encerrado';
    $request->validate([
        'novo_status' => 'required|in:' . $allowedStatuses
    ]);

    // Converte o histórico para array, se necessário
    $historico = is_array($processo->historico) ? $processo->historico : json_decode($processo->historico, true) ?? [];

    // Adiciona a nova atualização ao histórico
    $historico[] = [
        'status' => $request->novo_status,
        'data' => now()->format('d/m/Y H:i'),
        'responsavel' => auth()->user()->name
    ];

    // Atualiza o status e o histórico do processo
    $processo->update([
        'status_atual' => $request->novo_status,
        'historico' => $historico
    ]);

    return redirect()->route('processos.index')->with('success', 'Status atualizado!');
}

public function deleteHistorico(Processo $processo, $index)
{
    // Converte o histórico para array, se necessário
    $historico = is_array($processo->historico) ? $processo->historico : json_decode($processo->historico, true) ?? [];
    
    // Verifica se o índice existe
    if (isset($historico[$index])) {
        // Remove o item do histórico
        array_splice($historico, $index, 1);
        
        // Atualiza o registro no banco
        $processo->update(['historico' => $historico]);
        return redirect()->back()->with('success', 'Registro de histórico removido com sucesso!');
    } else {
        return redirect()->back()->with('error', 'Registro de histórico não encontrado.');
    }
}

public function destroy(Processo $processo)
{
    try {
        $processo->delete();
        return redirect()->route('processos.index')->with('success', 'Processo excluído com sucesso!');
    } catch (\Exception $e) {
        return redirect()->route('processos.index')->with('error', 'Erro ao excluir processo: ' . $e->getMessage());
    }
}




    // Visualização do cliente
    public function meusProcessos()
    {
        $processos = Processo::where('user_id', auth()->id())->get();
        return view('processos.meus', compact('processos'));
    }
}