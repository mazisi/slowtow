<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewalDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function licence_renewal()
    {
        return $this->belongsTo(LicenceRenewal::class);
    }
}
