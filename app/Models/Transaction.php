<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',    // 'entrada' ou 'saida'
        'valor',   // float
        'descricao',
    ];

    protected $casts = [
        'valor' => 'float', // garante que valor seja float
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
