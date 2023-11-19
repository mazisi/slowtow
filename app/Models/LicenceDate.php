<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenceDate extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $guarded = [];

    function licence(){
        return $this->belongsTo(Licence::class);
    }
}
