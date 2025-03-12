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

// Método para exibir a view de pagamento
public function pagamento()
{
    $user = Auth::user();
    $now = Carbon::now();
    
    $parcelas = ControleFinanceiro::where('user_id', $user->id)
        ->whereMonth('data_vencimento', $now->month)
        ->whereYear('data_vencimento', $now->year)
        ->where('status_pagamento', 'pendente')
        ->orderBy('data_vencimento')
        ->get();

    return view('controle_financeiro.pagamento', compact('parcelas'));
}

// Método para gerar o QR Code PIX
public function gerarPix(ControleFinanceiro $parcela)
{
    // Verifica se a parcela pertence ao usuário logado
    if ($parcela->user_id !== Auth::id()) {
        abort(403, 'Acesso não autorizado');
    }

    // Gera o payload PIX
    $pixPayload = $this->generatePixPayload($parcela->valor);

    // Gera o QR Code
    $renderer = new \BaconQrCode\Renderer\Image\SvgImageBackEnd();
    $renderer = new \BaconQrCode\Renderer\ImageRenderer(
        new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
        $renderer
    );
    
    $qrCode = (new \BaconQrCode\Writer($renderer))->writeString($pixPayload);

    return view('controle_financeiro.pagamento', [
        'parcelas' => $this->pagamento()->parcelas,
        'qrCode' => $qrCode,
        'valorParcela' => $parcela->valor
    ]);
}

private function generatePixPayload($amount)
{
    $pixKey = env('PIX_KEY');
    $merchantName = substr(env('MERCHANT_NAME'), 0, 25);
    $merchantCity = substr(env('MERCHANT_CITY'), 0, 15);
    $formattedAmount = number_format($amount, 2, '.', '');

    // Construção do payload seguindo padrão oficial
    $payload = "000201" // Payload inicial
        . "26" // Merchant Account Information
        . $this->buildMerchantAccountInfo($pixKey)
        . "52040000" // Merchant Category Code
        . "5303986" // Moeda (BRL)
        . "54" . str_pad(strlen($formattedAmount), 2, '0', STR_PAD_LEFT) . $formattedAmount
        . "5802BR" // País
        . "59" . str_pad(strlen($merchantName), 2, '0', STR_PAD_LEFT) . $merchantName
        . "60" . str_pad(strlen($merchantCity), 2, '0', STR_PAD_LEFT) . $merchantCity
        . "62070503***" // Additional Data Field
        . "6304"; // CRC16 placeholder

    $crc = $this->calculateCRC16($payload);
    return $payload . $crc;
}

// Métodos auxiliares (já existentes)
private function buildMerchantAccountInfo($pixKey)
{
    $gui = "0014BR.GOV.BCB.PIX"; // GUI fixo
    $pixKeyType = "01"; // Tipo de chave (01 = chave aleatória)
    $pixKeyLength = str_pad(strlen($pixKey), 2, '0', STR_PAD_LEFT);
    
    $merchantInfo = $gui . $pixKeyType . $pixKeyLength . $pixKey;
    $merchantInfoLength = str_pad(strlen($merchantInfo), 2, '0', STR_PAD_LEFT);
    
    return $merchantInfoLength . $merchantInfo;
}

private function calculateCRC16($payload)
{
    $polynomial = 0x1021;
    $result = 0xFFFF;

    // Otimização para grandes payloads
    for ($offset = 0; $offset < strlen($payload); $offset++) {
        $result ^= ord($payload[$offset]) << 8;
        for ($bit = 0; $bit < 8; $bit++) {
            $result = ($result & 0x8000) 
                ? (($result << 1) ^ $polynomial) 
                : ($result << 1);
            $result &= 0xFFFF;
        }
    }
    
    return strtoupper(str_pad(dechex($result), 4, '0', STR_PAD_LEFT));
}
}