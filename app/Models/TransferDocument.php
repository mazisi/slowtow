<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function licence_transfer()
    {
        return $this->belongsTo(LicenceTransfer::class);
    }
}
