<?php

// app/Models/Processo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Processo extends Model
{
    protected $fillable = [
        'user_id', 
        'numero_processo',
        'descricao',
        'status_atual',
        'historico'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}