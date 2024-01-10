<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    public function company_documents()
    {
       return $this->hasMany(CompanyDocument::class);
    }

    public function users()
    {
       return $this->belongsToMany(User::class);
    }

    public function people()
    {
       return $this->belongsToMany(People::class)->withPivot('position','id');
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
