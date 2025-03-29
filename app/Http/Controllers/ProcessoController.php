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
        $processos = $query->latest()->paginate(8);
        $clientes = User::all();
        return view('processos.index', compact('processos', 'clientes'));
    }
    


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'numero_processo' => 'required|unique:processos',
            'descricao' => 'required|min:1'
        ]);

        try {
            $processo = Processo::create([
                'user_id' => $request->user_id,
                'numero_processo' => $request->numero_processo,
                'descricao' => $request->descricao,
                'status_atual' => 'protocolado',
            ]);

            // Cria o primeiro registro no histórico
            Historico::create([
                'processo_id' => $processo->id, // Usa o ID do processo
                'status_atual' => 'protocolado',
                'created_at' => now(),
            ]);

            return redirect()->route('processos.index')->with('success', 'Processo criado!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar processo: ' . $e->getMessage()]);
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
            $allowedStatuses = 'protocolado,audiencia_conciliação,acordo,audiencia_instrucao,aguardando_sentenca,sentenca,sentenca_primeiro_grau,recursos,aguardando_sentenca_tribunal,decisao_tribunal,encerrado';
            
            $request->validate([
                'novo_status' => 'required|in:' . $allowedStatuses,
                'data_status' => 'required|date'
            ]);
    
            // Cria novo registro no histórico
            Historico::create([
                'processo_id' => $processo->id,
                'status_atual' => $request->novo_status,
                'created_at' => Carbon::createFromFormat('Y-m-d\TH:i', $request->data_status),
            ]);
    
            // Atualiza status do processo
            $processo->update(['status_atual' => $request->novo_status]);
    
            return redirect()->route('processos.index')->with('success', 'Status atualizado!');
        }
        

        public function deleteHistorico(Processo $processo, Historico $historico)
        {
            $historico->delete();
            return redirect()->back()->with('success', 'Registro removido!');
        }
    

        public function destroy(Processo $processo)
        {
            try {
                // Exclui todos os históricos associados
                $processo->historicos()->delete(); // Certifique-se que o relacionamento se chama "historico"

                // Exclui o processo
                $processo->delete();

                return redirect()->route('processos.index')
                    ->with('success', 'Processo excluído com sucesso!');
            } catch (\Exception $e) {
                return redirect()->route('processos.index')
                    ->with('error', 'Erro ao excluir processo: ' . $e->getMessage());
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