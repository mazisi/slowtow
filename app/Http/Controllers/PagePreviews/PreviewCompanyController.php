<?php

namespace App\Http\Controllers\PagePreviews;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Licence;

class PreviewCompanyController extends Controller
{
    function preview($slug) {
        $company = Company::with('licences.licence_documents')->whereSlug($slug)->firstOrFail();
        $licence  = Licence::where('company_id',$company->id)->firstOrFail();
        return Inertia::render('PagePreviews/Company/CompanyPreview',
        ['company' => $company]);
    }
}
