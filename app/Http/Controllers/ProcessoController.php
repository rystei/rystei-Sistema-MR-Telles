<?php

// app/Http\Controllers/ProcessoController.php
namespace App\Http\Controllers;

use App\Models\Processo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Historico;

class ProcessoController extends Controller
{
    // Listar todos os processos (para admin)
    public function index(Request $request)
    {
        $query = Processo::with('cliente');
    
        // Filtra pelo nome do cliente (utilizando relacionamento)
        if ($request->filled('cliente')) {
            $cliente = $request->cliente;
            $query->whereHas('cliente', function($q) use ($cliente) {
                $q->where('name', 'LIKE', '%' . $cliente . '%');
            });
        }
    
        // Filtra pelo número do processo
        if ($request->filled('numero_processo')) {
            $numero = $request->numero_processo;
            $query->where('numero_processo', 'LIKE', '%' . $numero . '%');
        }
    
        // Pagina os resultados (por exemplo, 10 processos por página)
        $processos = $query->paginate(8);
        $clientes = User::all();
        return view('processos.index', compact('processos', 'clientes'));
    }
    


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'numero_processo' => 'required|unique:processos',
            'descricao' => 'required|min:1' // Reduza para teste ou corrija o input
        ]);
        //dd();
        try {
            $p = Processo::create([
                'user_id' => $request->user_id,
                'numero_processo' => $request->numero_processo,
                'descricao' => $request->descricao,
            ]);
            //dd($p);
            Historico::create([
                'created_at' => Carbon::now(),
                'processo_id' =>$p->numero_processo,
            ]);
           // dd();
            return redirect()->route('processos.index')->with('success', 'Processo criado!');

        } catch (\Exception $e) {
           // dd($e);
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
                'novo_status' => 'required|in:' . $allowedStatuses,
                'data_status' => 'required|date' // Validação para garantir que a data seja válida
            ]);
        
            // Converte o histórico para array, se necessário
            $historico = is_array($processo->historico)
                ? $processo->historico
                : json_decode($processo->historico, true) ?? [];
        
            // Formata a data selecionada para o formato desejado "d/m/Y H:i"
            $dataFormatada = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->data_status)
                                ->format('d/m/Y H:i');
        
            // Adiciona a nova atualização ao histórico com a data formatada
            $historico[] = [
                'status' => $request->novo_status,
                'data' => $dataFormatada,
                'responsavel' => auth()->user()->name
            ];
        
            // Atualiza o status e o histórico do processo
            $processo->update([
                'status_atual' => $request->novo_status,
                'historico' => json_encode($historico)
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


        public function meusProcessos(Request $request)
    {
        $query = Processo::where('user_id', auth()->id());

        if ($request->filled('numero_processo')) {
            $numero = $request->numero_processo;
            $query->where('numero_processo', 'LIKE', '%' . $numero . '%');
        }

        $processos = $query->get();

        return view('processos.meus', compact('processos'));
    }

    public function meusProcessosDetalhes(Processo $processo)
    {
        if ($processo->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado a este processo.');
        }

        return view('processos.meus_detalhes', compact('processo'));
    }
}