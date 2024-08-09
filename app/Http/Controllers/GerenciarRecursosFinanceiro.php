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
            'total_amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'installments' => 'required|integer|min:1'
        ]);

        $totalAmount = $request->input('total_amount');
        $percentage = $request->input('percentage');
        $installments = $request->input('installments');

        // Calcula o valor da cobrança e o valor da parcela
        $chargeAmount = ($percentage / 100) * $totalAmount;
        $installmentAmount = $chargeAmount / $installments;

        // Gera o payload Pix
        $pixPayload = $this->generatePixPayload($chargeAmount);

        // Gera o QR Code
        $qrCode = new QrCode($pixPayload);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Codifica o QR Code em base64
        $qrCodeData = base64_encode($result->getString());

        return response()->json([
            'charge_amount' => $chargeAmount,
            'installment_amount' => $installmentAmount,
            'qr_code' => $qrCodeData
        ]);
    }

    private function generatePixPayload($amount)
    {
        $pixKey = env('PIX_KEY');
        $merchantName = env('MERCHANT_NAME');
        $merchantCity = env('MERCHANT_CITY');

        $merchantName = substr($merchantName, 0, 25);
        $merchantCity = substr($merchantCity, 0, 15);

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

        return strtoupper(dechex($resultado));
    }
}
