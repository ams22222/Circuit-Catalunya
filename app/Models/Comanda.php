<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'data_solicitud',
        'estat_comanda',
        'espai_id',
        'entrades'
    ];
}