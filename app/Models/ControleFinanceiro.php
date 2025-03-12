<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControleFinanceiro extends Model
{
    use HasFactory;

    protected $table = 'controle_financeiro';

    protected $fillable = [
        'user_id',
        'parcela_numero',
        'valor',
        'data_vencimento',
        'status_pagamento',
        'data_pagamento',
        'lote',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}