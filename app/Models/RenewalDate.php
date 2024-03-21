<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewalDate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    function renewal(){
        return $this->belongsTo(LicenceRenewal::class, 'renewal_id');
    }

}
