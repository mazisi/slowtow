<?php

namespace App\Http\Controllers\PagePreviews;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;
use App\Http\Controllers\Controller;

class LicencePreviewController extends Controller
{
    function preview($slug) {
        $licence  = 
        Licence::with('licence_documents','company','people','nominations.nomination_documents','transfers','alterations.documents','licence_stage_dates')
        ->whereSlug($slug)->firstOrFail();
        $transfer = LicenceTransfer::with('transfer_documents')->where('licence_id', $licence->id)->get();
        $licenceTypes = LicenceType::get();
        return Inertia::render('PagePreviews/Licences/LicencePreview', 
        ['licence' => $licence, 'transfer' => $transfer,
        'licenceTypes' => $licenceTypes
    ]);
    }
}
