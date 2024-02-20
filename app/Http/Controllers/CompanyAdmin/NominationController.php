<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\NominationDocument;
use App\Http\Controllers\Controller;

class NominationController extends Controller
{
    public function index() {
        $licence = Licence::whereSlug(request('slug'))->first();
        $nomination_years = Nomination::where('licence_id',$licence->id)->latest()->paginate(10);
        return Inertia::render('CompanyAdmin/Nominations/MyNominations',[
            'nomination_years' => $nomination_years,
             'licence' => $licence]);
    }

    public function show($slug) {
        $nomination = Nomination::with('licence','people','merged_document','nomination_documents')->whereSlug($slug)->first();

        return Inertia::render('CompanyAdmin/Nominations/ViewMyNomination',[
                'nomination' => $nomination]);
    }
}
