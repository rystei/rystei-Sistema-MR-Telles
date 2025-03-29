<?php

// app/Models/Processo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Processo extends Model
{
    protected $fillable = [
        'user_id', 
        'numero_processo',
        'descricao',
        'status_atual',
        'id',
    ];
   
    public function historicos(): HasMany
    {
        return $this->hasMany(Historico::class, 'processo_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function fluxoStatus()
    {
        return [
            'protocolado' => ['audiencia_conciliação'],
            'audiencia_conciliação' => ['acordo', 'sem_acordo'],
            'acordo' => ['sentenca', 'encerrado'],
            'sem_acordo' => ['audiencia_instrucao'],
            'audiencia_instrucao' => ['aguardando_sentenca'],
            'aguardando_sentenca' => ['sentenca_primeiro_grau'],
            'sentenca_primeiro_grau' => ['recursos', 'encerrado'],
            'recursos' => ['aguardando_sentenca_tribunal'],
            'aguardando_sentenca_tribunal' => ['decisao_tribunal'],
            'decisao_tribunal' => ['encerrado'],
            'encerrado' => []
        ];
    }

    public function transicoesPermitidas()
    {
        return self::fluxoStatus()[$this->status_atual] ?? [];
    }

    public function statusFormatado($status = null)
    {
        $nomes = [
            'protocolado'                 => 'Protocolado',
            'audiencia_conciliação'       => 'Audiência de Conciliação',
            'acordo'                      => 'Acordo',
            'audiencia_instrucao'         => 'Audiência de Instrução',
            'aguardando_sentenca'         => 'Aguardando Sentença',
            'sentenca'                    => 'Sentença',
            'sentenca_primeiro_grau'      => 'Sentença de Primeiro Grau',
            'recursos'                    => 'Recursos',
            'aguardando_sentenca_tribunal' => 'Aguardando Sentença no Tribunal',
            'decisao_tribunal'            => 'Decisão do Tribunal',
            'encerrado'                   => 'Encerrado'
        ];
    
        // Se um status for passado, use-o; senão, use o atual
        $status = $status ?? $this->status_atual;
    
        return $nomes[$status] ?? ucwords(str_replace('_', ' ', $status));
    }
    
}