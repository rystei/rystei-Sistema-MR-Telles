<?php

namespace App\Http\Controllers;

use App\Models\PixTransaction;
use Illuminate\Http\Request;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Support\Facades\Log;

class GerenciarRecursosFinanceiro extends Controller
{
    public function index()
    {
        $generatedPix = PixTransaction::latest()->get();
        return view('financeiro.index', compact('generatedPix'));
    }

    public function calculate(Request $request)
    {
        Log::debug('Dados recebidos:', $request->all());
        
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0.01',
            'client_name' => 'required|string|max:255'
        ]);

        Log::debug('Dados validados:', $validated);

        try {
            $pixPayload = $this->generatePixPayload($validated['total_amount']);
            Log::debug('Payload PIX gerado:', ['payload' => $pixPayload]);
            
            $renderer = new ImageRenderer(
                new RendererStyle(300),
                new SvgImageBackEnd()
            );
            $qrCode = (new Writer($renderer))->writeString($pixPayload);

            $pix = PixTransaction::create([
                'client_name' => $validated['client_name'],
                'total_amount' => $validated['total_amount'],
                'pix_payload' => $pixPayload,
                'transaction_date' => now()
            ]);

            return view('financeiro.index', [
                'qrCode' => $qrCode,
                'pixPayload' => $pixPayload,
                'pixData' => $pix,
                'generatedPix' => PixTransaction::latest()->get(),
                'success' => 'PIX gerado com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao gerar PIX:', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Erro ao gerar PIX: ' . $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        try {
            $pix = PixTransaction::findOrFail($id);
            $pix->delete();
            
            return redirect()->route('financeiro')
                ->with('success', 'PIX excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao excluir PIX: ' . $e->getMessage()]);
        }
    }

    private function generatePixPayload($amount)
    {
        $pixKey = env('PIX_KEY');
        $merchantName = substr(env('MERCHANT_NAME'), 0, 25);
        $merchantCity = substr(env('MERCHANT_CITY'), 0, 15);
        $formattedAmount = number_format($amount, 2, '.', '');

        $payload = "000201"
            . "26"
            . $this->buildMerchantAccountInfo($pixKey)
            . "52040000"
            . "5303986"
            . "54" . str_pad(strlen($formattedAmount), 2, '0', STR_PAD_LEFT) . $formattedAmount
            . "5802BR"
            . "59" . str_pad(strlen($merchantName), 2, '0', STR_PAD_LEFT) . $merchantName
            . "60" . str_pad(strlen($merchantCity), 2, '0', STR_PAD_LEFT) . $merchantCity
            . "62070503***"
            . "6304";

        $crc = $this->calculateCRC16($payload);
        return $payload . $crc;
    }

    private function buildMerchantAccountInfo($pixKey)
    {
        $gui = "0014BR.GOV.BCB.PIX";
        $key = "01" . str_pad(strlen($pixKey), 2, '0', STR_PAD_LEFT) . $pixKey;
        $merchantInfo = $gui . $key;
        return str_pad(strlen($merchantInfo), 2, '0', STR_PAD_LEFT) . $merchantInfo;
    }

    private function calculateCRC16($payload)
    {
        $polynomial = 0x1021;
        $result = 0xFFFF;

        for ($offset = 0; $offset < strlen($payload); $offset++) {
            $result ^= ord($payload[$offset]) << 8;
            for ($bit = 0; $bit < 8; $bit++) {
                $result = ($result & 0x8000) ? (($result << 1) ^ $polynomial) : ($result << 1);
                $result &= 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($result), 4, '0', STR_PAD_LEFT));
    }
}