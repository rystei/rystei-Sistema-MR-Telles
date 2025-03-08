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
            'total_parcelas' => 'required|integer|min:1',
            'valor' => 'required|numeric',
            'dia_fixo' => 'nullable|integer|min:1|max:31',
            'data_vencimento' => 'required_without:dia_fixo|date',
        ]);

        $valor = str_replace(['.', ','], ['', '.'], $request->valor);
        $valor = (float) $valor;

        if ($request->dia_fixo) {
            $currentDate = Carbon::now();
            $diaFixo = $request->dia_fixo;

            if ($currentDate->day > $diaFixo) {
                $dataVencimento = Carbon::create($currentDate->year, $currentDate->month, $diaFixo)->addMonth();
            } else {
                $dataVencimento = Carbon::create($currentDate->year, $currentDate->month, $diaFixo);
            }
        } else {
            $dataVencimento = Carbon::parse($request->data_vencimento);
        }

        for ($i = 1; $i <= $request->total_parcelas; $i++) {
            ControleFinanceiro::create([
                'user_id' => $request->user_id,
                'parcela_numero' => $i,
                'valor' => $valor,
                'data_vencimento' => $dataVencimento->copy()->addMonths($i - 1),
                'status_pagamento' => 'pendente',
            ]);
        }

        return redirect()->route('controle_financeiro.index')
                         ->with('success', 'Parcelas criadas com sucesso.');
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