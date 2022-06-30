<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function people()
    {
        return $this->belongsTo(People::class);
    }
}
