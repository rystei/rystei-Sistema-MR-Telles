<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Processar a notificação do Pix aqui
        // Ex: Atualizar o status da transação no banco de dados

        return response()->json(['status' => 'success'], 200);
    }
}
