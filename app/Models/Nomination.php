<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nomination extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function people(){
        return $this->belongsToMany(People::class)->withPivot('relationship','terminated_at','id');
    }

    public function licence(){
        return $this->belongsTo(Licence::class);
    }
}