<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DuplicateOriginal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }
    
   function duplicate_documents() {
    return $this->hasMany(DuplicateOriginalDoc::class);
   }

 
}
