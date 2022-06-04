<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $guarded = [];

    public function documents()
    {
       return $this->hasMany(Document::class);
    }
    
    public function users()
    {
       return $this->belongsToMany(User::class);
    }

    public function licences()
    {
       return $this->hasMany(Licence::class);
    }

    

   

    public function temporal_licences()
    {
       return $this->hasMany(TemporalLicence::class);
    }
}
