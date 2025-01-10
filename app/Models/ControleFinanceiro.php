<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControleFinanceiro extends Model
{
    use HasFactory;

    protected $table = 'controle_financeiro';

    protected $fillable = [
        'cliente_id',
        'parcela_numero',
        'valor',
        'data_vencimento',
        'status_pagamento',
        'data_pagamento',
        'notificado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
