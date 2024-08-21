<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PixPaymentNotification;
use Illuminate\Http\Request;

class PixWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // Validar e processar os dados recebidos do webhook
        if (isset($data['status']) && $data['status'] == 'CONFIRMADO') {
            // Detalhes do pagamento
            $paymentDetails = [
                'amount' => $data['valor'],
                'date' => now()->toDateTimeString(),
            ];

            // Encontre o usuário para quem a notificação deve ser enviada
            $user = User::find($data['user_id']);

            // Enviar a notificação
            if ($user) {
                $user->notify(new PixPaymentNotification($paymentDetails));
            }
        }

        return response()->json(['status' => 'success']);
    }
}
