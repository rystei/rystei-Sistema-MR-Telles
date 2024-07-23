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
            'amount' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'installments' => 'required|integer|min:1'
        ]);

        try {
            // Captura dos dados da requisição
            $amount = $request->input('amount');
            $discountPercentage = $request->input('discount_percentage');
            $installments = $request->input('installments');

            // Cálculo dos valores
            $discountAmount = ($discountPercentage / 100) * $amount;
            $finalAmount = $amount - $discountAmount;
            $installmentAmount = $finalAmount / $installments;

            // Geração do QR Code Pix
            $pixPayload = $this->generatePixPayload($finalAmount);

            // Criação do QR Code
            $qrCode = new QrCode($pixPayload);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $qrCodeData = base64_encode($result->getString());

            // Retorno dos dados em formato JSON
            return response()->json([
                'final_amount' => $finalAmount,
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
        $pixKey = 'sua-chave-pix-aqui';
        $merchantName = 'Nome do Advogado';
        $merchantCity = 'Cidade';

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
