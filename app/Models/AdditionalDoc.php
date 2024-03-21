<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalDoc extends Model
{
    use HasFactory;

    protected $guarded = [];

    function licence() {
        return $this->belongsTo(Licence::class);
    }

    public function modelable()
    {
        return $this->morphTo();
    }
}
