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

    // app/Http\Controllers\ProcessoController.php
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

    // Visualização do cliente
    public function meusProcessos()
    {
        $processos = Processo::where('user_id', auth()->id())->get();
        return view('processos.meus', compact('processos'));
    }
}