<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Http\Controllers\Controller;

class CompanyRegistrationController extends Controller
{
    public function registration(Request $request){
        $licence = Licence::with('company', 'licence_stage_dates', 'documents','additional_docs')->whereSlug($request->slug)->first();
    
        return Inertia::render('CompanyAdmin/Licences/Registration',[
            'licence' => $licence,]);
    }
}
