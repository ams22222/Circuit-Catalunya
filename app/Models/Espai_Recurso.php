<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espai_Recurso extends Model
{
    use HasFactory;

    protected $table = 'espai__recursos';

    protected $fillable = [
        'recurso_id',
        'espai_id'
    ];

    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'recurso_id');
    }

    public function espai()
    {
        return $this->belongsTo(Espai::class, 'espai_id');
    }
}
