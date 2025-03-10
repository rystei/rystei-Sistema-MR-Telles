<?php

namespace App\Http\Controllers;

use App\Models\ControleFinanceiro;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ControleFinanceiroController extends Controller
{
    public function index()
    {
        $clientes = User::with(['controleFinanceiro' => function ($query) {
            $query->orderBy('data_vencimento');
        }])->orderBy('name')->get();

        return view('controle_financeiro.index', compact('clientes'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('controle_financeiro.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_parcelas' => 'required|integer|min:1|max:360',
            'valor' => 'required|numeric|min:0.01',
            'mes_inicio' => 'required|date_format:Y-m'
        ]);
    
        try {
            // Converter valor
            $valor = (float) str_replace(['.', ','], ['', '.'], $request->valor);
            
            // Gerar lote
            $loteId = Carbon::now()->format('YmdHis');
    
            // Determinar data base
            $dataBase = $this->calcularDecimoDiaUtil(Carbon::createFromFormat('Y-m', $request->mes_inicio));
    
            // Criar parcelas
            $parcelas = [];
            for ($i = 0; $i < $request->total_parcelas; $i++) {
                $dataVencimento = $this->calcularDecimoDiaUtil($dataBase->copy()->addMonths($i));
    
                $parcelas[] = [
                    'lote' => $loteId,
                    'user_id' => $request->user_id,
                    'parcela_numero' => $i + 1,
                    'valor' => $valor,
                    'data_vencimento' => $dataVencimento->toDateString(),
                    'descricao' => $request->descricao,
                    'status_pagamento' => 'pendente',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
    
            ControleFinanceiro::insert($parcelas);
    
            return redirect()->route('controle_financeiro.index')
                            ->with('success', 'Parcelas criadas com sucesso!');
    
        } catch (\Exception $e) {
            \Log::error('Erro ao criar parcelas: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erro ao criar parcelas: ' . $e->getMessage()]);
        }
    }
    
    private function calcularDecimoDiaUtil(Carbon $data)
    {
        $data = $data->copy()->startOfMonth();
        $contadorDiasUteis = 0;
    
        while ($contadorDiasUteis < 10) {
            if (!$data->isWeekend()) {
                $contadorDiasUteis++;
            }
            if ($contadorDiasUteis < 10) {
                $data->addDay();
            }
        }
    
        return $data;
    }
    // Métodos auxiliares
    private function ajustarParaDiaUtil(Carbon $data): Carbon
    {
        while ($data->isWeekend()) {
            $data->addDay();
        }
        return $data;
    }
    
    private function ajustarUltimoDiaMes(Carbon $data, ?int $diaOriginal): Carbon
    {
        if ($diaOriginal && $data->day !== $diaOriginal) {
            return $data->endOfMonth();
        }
        return $data;
    }

    public function atualizarStatus(Request $request, $id)
    {
        $parcela = ControleFinanceiro::findOrFail($id);
        $parcela->update([
            'status_pagamento' => 'pago',
            'data_pagamento' => now()
        ]);

        return redirect()->back()->with('success', 'Pagamento confirmado com sucesso.');
    }

    public function destroy($id)
    {
        $parcela = ControleFinanceiro::findOrFail($id);
        $parcela->delete();
        
        return redirect()->back()->with('success', 'Parcela excluída com sucesso.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $clientes = User::with('controleFinanceiro')
            ->where('name', 'LIKE', "%$search%")
            ->orderBy('name')
            ->get();

        return view('controle_financeiro.index', compact('clientes'));
    }

    public function minhasParcelas(Request $request)
{
    $user = Auth::user();
    
    $parcelas = ControleFinanceiro::where('user_id', $user->id)
        ->when($request->search, function($query) use ($request) {
            $search = $request->search;
            return $query->where(function($q) use ($search) {
                $q->where('parcela_numero', 'LIKE', "%$search%")
                  ->orWhere('data_vencimento', 'LIKE', "%$search%");
            });
        })
        ->orderBy('data_vencimento')
        ->get();

    return view('controle_financeiro.minhas', compact('parcelas', 'user'));
}
}