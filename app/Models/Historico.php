<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Historico extends Model
{
    /** @use HasFactory<\Database\Factories\HistoricoFactory> */
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function processo(): BelongsTo
    {
        return $this->belongsTo(Processo::class, 'processo_id', 'id');
    }
}