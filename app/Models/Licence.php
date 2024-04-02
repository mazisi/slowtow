<?php

namespace App\Models;

use App\Models\LicenceDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Licence extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [];
    
//     protected $casts = [
//       'licence_date' => 'date',
//   ];

    protected $cascadeDeletes = ['nominations','licence_renewals','alterations','transfers'];

  

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
    public function duplicate_originals()
    {
       return $this->hasMany(DuplicateOriginal::class);
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


    public function licence_stage_dates(){
      return $this->hasMany(LicenceDate::class, 'licence_id');
    }

    public function documents(){
      return $this->hasMany(LicenceDocument::class, 'licence_id');
    }

   //  public function additional_docs()
   //  {
   //     return $this->hasMany(AdditionalDoc::class);
   //  }

   public function additionalDocs()
    {
        return $this->morphMany('App\Models\AdditionalDoc', 'modelable');
    }
    
    
}
