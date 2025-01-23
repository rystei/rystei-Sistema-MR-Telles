<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['title', 'start', 'end', 'description', 'color', 'all_day'];

    protected $casts = [
        'all_day' => 'boolean', // Converte automaticamente para true/false
    ];
}
