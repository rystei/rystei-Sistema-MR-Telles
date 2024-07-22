<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $chargeAmount = ($percentage / 100) * $totalAmount;
        $installmentAmount = $chargeAmount / $installments;

        return response()->json([
            'charge_amount' => $chargeAmount,
            'installment_amount' => $installmentAmount
        ]);
    }
}
