<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class GerenciarRecursosFinanceiro extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
            'percentage' => 'required|numeric|min:0|max:100',
            'installments' => 'required|integer|min:1'
        ]);
    
        // Remove pontos e substitui vírgula por ponto no valor total
        $totalAmount = str_replace(['.', ','], ['', '.'], $request->input('total_amount'));
        $percentage = $request->input('percentage');
        $installments = $request->input('installments');
    
        // Calcula o valor total a ser cobrado aplicando a porcentagem ao valor total recebido
        $chargeAmount = $totalAmount * ($percentage / 100);
    
        // Calcula o valor de cada parcela
        $installmentAmount = $chargeAmount / $installments;
    
        // Gera o payload Pix com o valor da parcela
        $pixPayload = $this->generatePixPayload($installmentAmount); // Gera o QR Code com o valor da parcela
    
        // Gera o QR Code
        $qrCode = new QrCode($pixPayload);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
    
        // Codifica o QR Code em base64
        $qrCodeData = base64_encode($result->getString());
    
        return response()->json([
            'charge_amount' => number_format($chargeAmount, 2, ',', '.'),  // Valor total formatado
            'installment_amount' => number_format($installmentAmount, 2, ',', '.'),  // Valor de cada parcela formatado
            'qr_code' => $qrCodeData  // QR Code gerado
        ]);
    }    

    private function generatePixPayload($amount)
{
    $pixKey = env('PIX_KEY');
    $merchantName = env('MERCHANT_NAME');
    $merchantCity = env('MERCHANT_CITY');

    $merchantName = substr($merchantName, 0, 25);
    $merchantCity = substr($merchantCity, 0, 15);

    // Formata o valor com ponto como separador decimal
    $formattedAmount = number_format($amount, 2, '.', '');

    $pixPayload = "000201"
        . "26490014BR.GOV.BCB.PIX0127{$pixKey}"
        . "520400005303986"
        . "5404{$formattedAmount}"
        . "5802BR"
        . "59" . str_pad(strlen($merchantName), 2, '0', STR_PAD_LEFT) . $merchantName
        . "60" . str_pad(strlen($merchantCity), 2, '0', STR_PAD_LEFT) . $merchantCity
        . "62070503***"
        . "6304";

    // Adiciona o CRC16
    $pixPayload .= $this->calculateCRC16($pixPayload);

    return $pixPayload;
}

private function calculateCRC16($payload)
{
    $polinomio = 0x1021;
    $resultado = 0xFFFF;

    for ($offset = 0; $offset < strlen($payload); $offset++) {
        $resultado ^= (ord($payload[$offset]) << 8);

        for ($bitwise = 0; $bitwise < 8; $bitwise++) {
            if (($resultado <<= 1) & 0x10000) {
                $resultado ^= $polinomio;
            }
            $resultado &= 0xFFFF;
        }
    }

    return strtoupper(str_pad(dechex($resultado), 4, '0', STR_PAD_LEFT));
}

}
