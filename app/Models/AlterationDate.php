<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlterationDate extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $guarded = [];

    function alteration() {
        return $this->belongsTo(Alteration::class);
        
    }
    
}
