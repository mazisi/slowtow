<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenceTransfer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function licence(){
       return $this->belongsTo(Licence::class);
    }

    public function old_company()
    {
       return $this->belongsTo(Company::class,'licence_transfare','old_company_id','licence_id');
    }
}
