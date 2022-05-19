<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Licence extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function company()
    {
       return $this->belongsTo(Company::class);
    }

    public function documents()
    {
       return $this->hasMany(Document::class);
    }

    
}
