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

    public function people()
    {
       return $this->belongsTo(People::class);
    }

    public function licence_documents()
    {
       return $this->hasMany(LicenceDocument::class);
    }
    public function nominations()
    {
       return $this->hasMany(Nomination::class);
    }

    public function alterations()
    {
       return $this->hasMany(Alteration::class);
    }

    public function licence_type()
    {
       return $this->belongsTo(LicenceType::class);
    }

    public function licence_renewals()
    {
       return $this->hasMany(LicenceRenewal::class);
    }

    public function transfers()
    {
       return $this->belongsToMany(Company::class, 'licence_transfers','licence_id','company_id' )->withPivot('date','status','slug');
    }

    public function old_company()
    {
       return $this->belongsToMany(Company::class,'licence_transfers','licence_id','old_company_id');
    }

    public function liquor_board_requests()
    {
       return $this->hasMany(LiquorBoardRequest::class);
    }


    
}
