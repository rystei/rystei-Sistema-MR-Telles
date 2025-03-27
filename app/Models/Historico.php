<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Historico extends Model
{
    use HasFactory;
    public $timestamps = false; // Adicione esta linha

    protected $casts = [
        'created_at' => 'datetime', // Adicione esta linha
    ];


    protected $fillable = [ // Adicione esta linha
        'processo_id', 
        'status_atual',
        'created_at'
    ];

    public function processo(): BelongsTo
    {
        return $this->belongsTo(Processo::class, 'processo_id');
    }
}