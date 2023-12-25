<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporalLicenceDocument extends Model
{
    use HasFactory;

    protected $guarded = [];
    
     public function getDocumentAttribute($value)
    {
        if(str_starts_with($value,"licence-")|| str_starts_with($value,"mrnlabs/")){

            return $value;
        }

        return "docs/".$value;
    }

    function temp_licence() {
        return $this->belongsTo(TemporalLicence::class);
    }
}
