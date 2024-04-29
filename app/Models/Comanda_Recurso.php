<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda_Recurso extends Model
{
    use HasFactory;

    protected $fillable = [
        'recurso_id',
        'comanda_id'
    ];

    public function recursos(){
        return $this->belongsToMany(Recurso::class);
    }
    
    public function comandas(){
        return $this->belongsToMany(Comanda::class);
    }
}
