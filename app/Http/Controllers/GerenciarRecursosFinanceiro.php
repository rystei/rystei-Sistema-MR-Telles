<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\PixPaymentNotification;
use Illuminate\Support\Facades\Notification;

class GerenciarRecursosFinanceiro extends Controller
{
    public function calculate(Request $request)
    {
        \Log::info('Início do método calculate');

        $request->validate([
            'total_amount' => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
            'percentage' => 'required|numeric|min:0|max:100',
            'installments' => 'required|integer|min:1'
        ]);

        \Log::info('Validação concluída');

        // Remove pontos e substitui vírgula por ponto no valor total
        $totalAmount = str_replace(['.', ','], ['', '.'], $request->input('total_amount'));
        $percentage = $request->input('percentage');
        $installments = $request->input('installments');

        \Log::info('Valores recebidos', ['total_amount' => $totalAmount, 'percentage' => $percentage, 'installments' => $installments]);

        // Calcula o valor total a ser cobrado aplicando a porcentagem ao valor total recebido
        $chargeAmount = $totalAmount * ($percentage / 100);

        \Log::info('Valor total calculado', ['chargeAmount' => $chargeAmount]);

        // Calcula o valor de cada parcela
        $installmentAmount = $chargeAmount / $installments;

        \Log::info('Valor de cada parcela calculado', ['installmentAmount' => $installmentAmount]);

        // Gera o payload Pix com o valor da parcela
        $pixPayload = $this->generatePixPayload($installmentAmount);

        \Log::info('Payload Pix gerado', ['pixPayload' => $pixPayload]);

        // Gera o QR Code
        $qrCode = new QrCode($pixPayload);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        \Log::info('QR Code gerado com sucesso');

        // Codifica o QR Code em base64
        $qrCodeData = base64_encode($result->getString());

        // Salva a transação no banco de dados
        // Criação da transação
        $transaction = new Transaction();
        $transaction->user_id = auth()->id(); // Supondo que o usuário esteja autenticado
        $transaction->amount = $chargeAmount;
        $transaction->status = 'pending';
        $transaction->save();

        \Log::info('Transação salva no banco de dados', ['transaction_id' => $transaction->id]);

        // Inclua o ID da transação na resposta JSON para uso posterior
        return response()->json([
            'transaction_id' => $transaction->id, // Adiciona o ID da transação
            'charge_amount' => number_format($chargeAmount, 2, ',', '.'),  // Valor total formatado
            'installment_amount' => number_format($installmentAmount, 2, ',', '.'),  // Valor de cada parcela formatado
            'qr_code' => $qrCodeData  // QR Code gerado
        ]);
    }

    public function confirmPayment($transactionId)
{
    \Log::info('Início do método confirmPayment', ['transaction_id' => $transactionId]);

    // Tente encontrar a transação com base no ID
    $transaction = Transaction::find($transactionId);

    // Verifique se a transação foi encontrada
    if (!$transaction) {
        \Log::warning('Transação não encontrada', ['transaction_id' => $transactionId]);
        return response()->json(['message' => 'Transação não encontrada.'], 404);
    }

    // Verifique se a transação já está marcada como paga
    if ($transaction->status === 'paid') {
        \Log::info('Transação já paga', ['transaction_id' => $transaction->id]);
        return response()->json(['message' => 'Transação já está paga.'], 400);
    }

    // Atualize o status da transação para 'paid'
    $transaction->status = 'paid';
    $transaction->paid_at = now();  // Adiciona a data e hora do pagamento
    $transaction->save();

    \Log::info('Status da transação atualizado para "paid"', ['transaction_id' => $transaction->id]);

    // Obtém o usuário logado
    $user = auth()->user();

    if ($user) {
        // Envie a notificação de pagamento ao usuário logado
        Notification::sendNow($user, new PixPaymentNotification($transaction));
        \Log::info('Notificação enviada ao usuário', ['user_id' => $user->id, 'email' => $user->email]);
    } else {
        \Log::warning('Usuário não autenticado ao tentar enviar notificação.');
    }

    // Notificação ao administrador (caso exista)
    $admin = User::where('role', 'admin')->first();
    if ($admin) {
        Notification::sendNow($admin, new PixPaymentNotification($transaction));
        \Log::info('Notificação enviada ao admin', ['admin_id' => $admin->id, 'email' => $admin->email]);
    } else {
        \Log::warning('Administrador não encontrado.');
    }

    return response()->json(['message' => 'Pagamento confirmado e notificação enviada.']);
}

    private function generatePixPayload($amount)
    {
        \Log::info('Início do método generatePixPayload', ['amount' => $amount]);

        $pixKey = env('PIX_KEY');
        $merchantName = env('MERCHANT_NAME');
        $merchantCity = env('MERCHANT_CITY');

        $merchantName = substr($merchantName, 0, 25);
        $merchantCity = substr($merchantCity, 0, 15);

        \Log::info('Informações do comerciante', ['merchantName' => $merchantName, 'merchantCity' => $merchantCity]);

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

        \Log::info('Payload Pix sem CRC16', ['pixPayload' => $pixPayload]);

        // Adiciona o CRC16
        $pixPayload .= $this->calculateCRC16($pixPayload);

        \Log::info('Payload Pix final', ['pixPayload' => $pixPayload]);

        return $pixPayload;
    }

    private function calculateCRC16($payload)
    {
        \Log::info('Início do método calculateCRC16');

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

        $crc16 = strtoupper(str_pad(dechex($resultado), 4, '0', STR_PAD_LEFT));

        \Log::info('CRC16 calculado', ['crc16' => $crc16]);

        return $crc16;
    }

    private function sendPaymentNotifications($transaction)
    {
        \Log::info('Início do método sendPaymentNotifications', ['transaction_id' => $transaction->id]);
    
        // Notifica o usuário e o administrador
        $user = $transaction->user;
        $admin = User::where('role', 'admin')->first(); // Assumindo que o advogado é o admin
    
        if ($user) {
            \Log::info('Enviando notificação para o usuário', ['user_id' => $user->id]);
            $user->notify(new PixPaymentNotification($transaction));
        }
    
        if ($admin) {
            \Log::info('Enviando notificação para o administrador', ['admin_id' => $admin->id]);
            $admin->notify(new PixPaymentNotification($transaction));
        }
    }    
}
