<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LicenceRenewalController extends Controller
{
    public function renewLicence($slug){
        $licence = Licence::with('company')->whereSlug($slug)->first();
       return Inertia::render('Licences/RenewLicence',['licence' => $licence]);
    }
}
