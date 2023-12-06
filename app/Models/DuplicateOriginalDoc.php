<?php

namespace App\Models;

use App\Models\Licence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DuplicateOriginalDoc extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }
    
   function duplicate_original() {
     return $this->belongsTo(DuplicateOriginal::class);
   }

 
}
