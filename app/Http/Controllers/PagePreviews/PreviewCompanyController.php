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
        // $company = Company::with('licences.licence_documents')->whereSlug($slug)->firstOrFail();

        $company = Company::whereSlug($slug)->with(['licences.licence_documents' => function ($query) {
            $query->where('document_type','Duplicate-Licence')
            ->orWhere('document_type','Original-Licence')
            ->orWhere('document_type','Payment To The Liquor Board');
            }])->firstOrFail();
// dd($company);

        // $licence  = Licence::where('company_id',$company->id)->firstOrFail();
        return Inertia::render('PagePreviews/Company/CompanyPreview',
        ['company' => $company]);
    }
}
