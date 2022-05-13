<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function documents()
    {
       return $this->hasMany(Document::class);
    }
    
    public function users()
    {
       return $this->hasMany(User::class);
    }

    public function licences()
    {
       return $this->hasMany(Licence::class);
    }
}
