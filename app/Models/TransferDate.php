<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    function transfer() {
        return $this->belongsTo(LicenceTransfer::class);

    }

}
