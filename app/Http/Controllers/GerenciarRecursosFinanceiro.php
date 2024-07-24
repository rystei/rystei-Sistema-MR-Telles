<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class GerenciarRecursosFinanceiro extends Controller
{
    public function calculate(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'installments' => 'required|integer|min:1'
        ]);

        try {
            // Captura dos dados da requisição
            $totalAmount = $request->input('total_amount');
            $percentage = $request->input('percentage');
            $installments = $request->input('installments');

            // Cálculo do valor a ser cobrado com base na porcentagem
            $chargeAmount = ($percentage / 100) * $totalAmount;

            // Cálculo do valor de cada parcela
            $installmentAmount = $chargeAmount / $installments;

            // Geração do QR Code Pix com o valor total a ser cobrado
            $pixPayload = $this->generatePixPayload($chargeAmount);

            // Criação do QR Code
            $qrCode = new QrCode($pixPayload);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $qrCodeData = base64_encode($result->getString());

            // Retorno dos dados em formato JSON
            return response()->json([
                'charge_amount' => $chargeAmount,
                'installment_amount' => $installmentAmount,
                'qr_code' => $qrCodeData
            ]);

        } catch (\Exception $e) {
            // Captura de qualquer exceção e retorno de uma mensagem de erro
            return response()->json([
                'error' => 'Erro interno no servidor. Por favor, tente novamente mais tarde.'
            ], 500);
        }
    }

    private function generatePixPayload($amount)
    {
        // Substitua essas informações pelos dados da sua chave PIX
        $pixKey = '100.990.599-67';
        $merchantName = 'Gustavo Telles';
        $merchantCity = 'Londrina';

        // Criação do payload do PIX
        $pixPayload = "00020126330014BR.GOV.BCB.PIX0114{$pixKey}5204000053039865405{$amount}5802BR5900{$merchantName}6000{$merchantCity}62070503***6304";

        // Cálculo do CRC16
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
