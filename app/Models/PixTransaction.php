<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PixTransaction extends Model
{
    use HasFactory;

    protected $table = 'pix_transactions_list';
    
    protected $fillable = [
        'client_name',
        'total_amount',
        'pix_payload',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total_amount' => 'decimal:2'
    ];
}